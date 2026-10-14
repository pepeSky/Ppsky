<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Identity;
use App\Services\MeWo\ActorAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IdentityController extends Controller
{
    public function __construct(private ActorAccess $access) {}
    private function permission(Request $request, string $permission): void
    {
        $this->access->require($request,$this->access->system($request),$permission);
    }
    public function index(Request $request)
    {
        $this->permission($request,'identities.view');
        $identities=Identity::with(['entity.nature','actors'])->where('user_id',$request->user()->id)->orderBy('id')->paginate(15);
        $canManage=$this->access->allows($request,$this->access->system($request),'identities.manage');
        return view('admin.identities.index',compact('identities','canManage'));
    }
    public function create(Request $request)
    {
        $this->permission($request,'identities.manage');
        $entities=Entity::with('nature')->whereDoesntHave('identities')->orderBy('name')->get();
        return view('admin.identities.create',compact('entities'));
    }
    public function store(Request $request)
    {
        $this->permission($request,'identities.manage');
        $data=$this->validated($request);
        $data['entity_id']=$request->validate(['entity_id'=>['required',Rule::exists('entities','id'),Rule::unique('identities','entity_id')]])['entity_id'];
        \Illuminate\Support\Facades\DB::transaction(function() use($request,$data) {
            Entity::whereKey($data['entity_id'])->lockForUpdate()->firstOrFail();
            if(Identity::where('entity_id',$data['entity_id'])->exists()) { throw \Illuminate\Validation\ValidationException::withMessages(['entity_id'=>'Esta entidad ya tiene una identidad; utilice su participación existente.']); }
            Identity::create($data+['user_id'=>$request->user()->id]);
        });
        return redirect()->route('admin.identities.index')->with('info','Identidad registrada.');
    }
    public function show(Request $request, Identity $identity)
    {
        $this->check($request,$identity,'identities.view');
        $identity->load(['entity.nature','actors']);
        $canManage=$this->access->allows($request,$this->access->system($request),'identities.manage');
        return view('admin.identities.show',compact('identity','canManage'));
    }
    public function edit(Request $request, Identity $identity)
    {
        $this->check($request,$identity,'identities.manage'); $identity->load('entity');
        return view('admin.identities.edit',compact('identity'));
    }
    public function update(Request $request, Identity $identity)
    {
        $this->check($request,$identity,'identities.manage');
        $identity->update($this->validated($request,$identity));
        return redirect()->route('admin.identities.show',$identity)->with('info','Identidad actualizada.');
    }
    public function destroy(Request $request, Identity $identity)
    {
        $this->check($request,$identity,'identities.manage');
        if($identity->actors()->exists() || $identity->system()->exists()) {
            return back()->withErrors(['identity'=>'Esta identidad tiene actores o un sistema asociado; conserve sus referencias.']);
        }
        $identity->delete();
        return redirect()->route('admin.identities.index')->with('info','Identidad eliminada.');
    }
    private function check(Request $request, Identity $identity, string $permission): void
    {
        $this->permission($request,$permission);
        abort_unless((int)$identity->user_id===(int)$request->user()->id,403);
    }
    private function validated(Request $request, ?Identity $identity=null): array
    {
        return app(\App\Services\MeWo\IdentityDetails::class)->validate($request,$identity);
    }
}
