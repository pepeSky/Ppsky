<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Cohesion;
use App\Models\Interaction;
use App\Services\MeWo\ActorAccess;
use App\Services\MeWo\InteractionAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class InteractionController extends Controller
{
    public function __construct(private ActorAccess $access, private InteractionAccess $interactions) {}
    private function requirePermission(Request $request, string $permission): void
    {
        $this->access->require($request,$this->access->system($request),$permission);
    }
    private function check(Request $request, Interaction $interaction, string $permission): void
    {
        $this->requirePermission($request,$permission);
        abort_unless($this->interactions->query($request)->whereKey($interaction->id)->exists(),404);
    }
    private function cohesions() { return Cohesion::whereIn('symbol',['0:0','0:1','1:1'])->get(); }
    public function index(Request $request)
    {
        $this->requirePermission($request,'actors.view');
        $interactions=$this->interactions->query($request)->with(['actorA.identity.entity','actorB.identity.entity','cohesion'])->orderBy('id')->paginate(15);
        $canManage=$this->access->allows($request,$this->access->system($request),'actors.interactions');
        return view('admin.interactions.index',compact('interactions','canManage'));
    }
    public function create(Request $request)
    {
        $this->requirePermission($request,'actors.interactions');
        $actors=$this->interactions->actors($request)->with('identity.entity')->get(); $cohesions=$this->cohesions();
        return view('admin.interactions.create',compact('actors','cohesions'));
    }
    private function validateCohesion(Request $request): array
    {
        return $request->validate(['cohesion_id'=>['required',Rule::exists('cohesions','id')->whereIn('symbol',['0:0','0:1','1:1'])]]);
    }
    public function store(Request $request)
    {
        $this->requirePermission($request,'actors.interactions'); $data=$this->validateCohesion($request);
        $actors=$request->validate(['actor_a_id'=>['required','integer','different:actor_b_id'],'actor_b_id'=>['required','integer']]);
        $a=$this->interactions->actors($request)->whereKey($actors['actor_a_id'])->firstOrFail();
        $b=$this->interactions->actors($request)->whereKey($actors['actor_b_id'])->firstOrFail();
        Interaction::firstOrCreate(['actor_a_id'=>min($a->id,$b->id),'actor_b_id'=>max($a->id,$b->id),'cohesion_id'=>$data['cohesion_id']]);
        return redirect()->route('admin.interactions.index')->with('info','Interacción registrada.');
    }
    public function show(Request $request, Interaction $interaction)
    {
        $this->check($request,$interaction,'actors.view'); $interaction->load(['actorA.identity.entity','actorB.identity.entity','cohesion']);
        $canManage=$this->access->allows($request,$this->access->system($request),'actors.interactions');
        return view('admin.interactions.show',compact('interaction','canManage'));
    }
    public function edit(Request $request, Interaction $interaction)
    {
        $this->check($request,$interaction,'actors.interactions'); $interaction->load(['actorA.identity.entity','actorB.identity.entity']); $cohesions=$this->cohesions();
        return view('admin.interactions.edit',compact('interaction','cohesions'));
    }
    public function update(Request $request, Interaction $interaction)
    {
        $this->check($request,$interaction,'actors.interactions'); $data=$this->validateCohesion($request);
        if(Interaction::where('actor_a_id',$interaction->actor_a_id)->where('actor_b_id',$interaction->actor_b_id)->where('cohesion_id',$data['cohesion_id'])->where('id','!=',$interaction->id)->exists()) { return back()->withErrors(['cohesion_id'=>'Esta interacción ya existe.']); }
        if($interaction->cohesion_id!=$data['cohesion_id'] && \App\Models\InteractionPeriod::where('interaction_id',$interaction->id)->exists()) { return back()->withErrors(['cohesion_id'=>'Conserve la interacción con tiempo registrado; agregue otra cohesión.']); }
        $interaction->update($data);
        return redirect()->route('admin.interactions.index');
    }
    public function destroy(Request $request, Interaction $interaction)
    {
        $this->check($request,$interaction,'actors.interactions');
        if(\App\Models\InteractionPeriod::where('interaction_id',$interaction->id)->exists()) { return back()->withErrors(['interaction'=>'Esta interacción tiene períodos registrados.']); }
        $interaction->delete(); return redirect()->route('admin.interactions.index');
    }
}
