<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Cohesion;
use App\Services\MeWo\ActorAccess;
use App\Services\MeWo\ActorRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ActorController extends Controller
{
    public function __construct(private ActorAccess $access, private ActorRegistry $registry) {}
    public function index(Request $request)
    {
        $system=$this->access->system($request);
        $this->access->require($request,$system,'actors.view');
        $actors=Actor::where(fn($q)=>$q->where('system_id',$system->id)->orWhere(fn($old)=>$old->whereNull('system_id')->whereHas('identity',fn($i)=>$i->where('user_id',$request->user()->id))))
            ->with(['identity.entity','cohesions','classifications.cohesion','originActor.system.identity.entity'])->orderBy('id')->paginate(20);
        $access=$this->access;
        return view('admin.actors.index',compact('actors','system','access'));
    }
    public function create(Request $request)
    {
        $system=$this->access->system($request); $this->access->require($request,$system,'actors.create');
        return $this->form($request,$system,new Actor());
    }
    public function importForm(Request $request)
    {
        $system=$this->access->system($request); $this->access->require($request,$system,'actors.create');
        return $this->form($request,$system,new Actor(),true);
    }
    public function importStore(Request $request)
    {
        $request->validate(['origin_system_id'=>['required','integer'],'origin_code'=>['required','string','max:64']]);
        return $this->store($request);
    }
    public function edit(Request $request, Actor $actor)
    {
        $system=$this->access->actor($request,$actor,'actors.edit');
        abort_if($actor->archived_at,409,'El actor está archivado.');
        return $this->form($request,$system,$actor);
    }
    private function form(Request $request, $system, Actor $actor, bool $importMode=false)
    {
        $identities=$this->access->identities($request,$system)->with('entity')->get();
        $cohesions=Cohesion::whereIn('symbol',['0:0','0:1','1:1'])->get();
        $members=$system->users()->get(); $access=$this->access;
        $actor->load(['cohesions','classifications.cohesion']);
        $natures=\App\Models\Nature::orderBy('name')->get();
        return view('admin.actors.form',compact('system','actor','identities','cohesions','members','access','importMode','natures'));
    }
    public function store(Request $request)
    {
        $system=$this->access->system($request); $this->access->require($request,$system,'actors.create');
        $data=$this->validateData($request);
        $actor=DB::transaction(function() use($request,$system,$data) {
            \App\Models\System::whereKey($system->id)->lockForUpdate()->firstOrFail();
            $origin=null;
            if (!empty($data['origin_code'])) {
                if (!empty($data['identity_id'])) { throw ValidationException::withMessages(['identity_id'=>'La importación conserva la identidad del actor de origen.']); }
                $origin=Actor::where('system_id',$data['origin_system_id'])->where(fn($q)=>$q->where('code',$data['origin_code'])->orWhereHas('classifications',fn($c)=>$c->where('code',$data['origin_code'])))->whereNull('archived_at')->first();
                if (!$origin || (int)$origin->system_id===$system->id) { throw ValidationException::withMessages(['origin_code'=>'La referencia externa no corresponde a un actor vigente de otro sistema.']); }
                $identity=$origin->identity;
            } else { $identity=$this->resolveIdentity($request,$system,$data); }
            $name=trim($data['name'] ?? $origin?->display_name ?? $identity->display_name);
            $actor=new Actor(['identity_id'=>$identity->id,'system_id'=>$system->id,'origin_actor_id'=>$origin?->id,'name'=>$name===$identity->display_name ? null : $name]);
            $this->apply($request,$system,$actor,$data);
            $actor->save();
            $this->registry->preservePrimary($actor);
            $this->registry->refreshAccount($system->id,$actor->account_user_id);
            $this->syncCohesions($request,$system,$actor,$data);
            $this->registry->audit($system->id,$request->user()->id,$actor->id,'registered',$actor->getAttributes());
            return $actor;
        });
        return redirect()->route('admin.actors.show',$actor)->with('info','Actor registrado en este sistema.');
    }
    public function update(Request $request, Actor $actor)
    {
        $system=$this->access->actor($request,$actor,'actors.edit');
        $data=$this->validateData($request);
        DB::transaction(function() use($request,$system,$actor,$data) {
            \App\Models\System::whereKey($system->id)->lockForUpdate()->firstOrFail();
            $actor=Actor::whereKey($actor->id)->lockForUpdate()->firstOrFail();
            abort_if($actor->archived_at,409);
            // Identity and origin cannot be replaced after registering a participation.
            if(isset($data['identity_id']) && (int)$data['identity_id']!==$actor->identity_id) {
                throw ValidationException::withMessages(['identity_id'=>'La identidad es permanente; registre otro actor.']);
            }
            if (!$actor->system_id) { $this->access->require($request,$system,'actors.create'); $actor->system_id=$system->id; }
            $previousAccount=$actor->account_user_id;
            $this->apply($request,$system,$actor,$data); $changes=$actor->getDirty(); $actor->save();
            $this->registry->preservePrimary($actor);
            $this->registry->refreshAccount($system->id,$previousAccount);
            $this->registry->refreshAccount($system->id,$actor->account_user_id);
            $this->syncCohesions($request,$system,$actor,$data);
            $this->registry->audit($system->id,$request->user()->id,$actor->id,'updated',$changes);
        });
        return redirect()->route('admin.actors.show',$actor)->with('info','Actor actualizado.');
    }
    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'=>['nullable','string','max:255','regex:/\\S/u'],
            'identity_id'=>[$request->isMethod('POST') ? 'required_without_all:name,origin_code' : 'nullable','nullable','integer'], 'origin_system_id'=>['nullable','required_with:origin_code','integer'], 'origin_code'=>['nullable','required_with:origin_system_id','string','max:64'],
            'new_identity'=>['nullable','boolean'],
            'nature_id'=>['nullable','integer',Rule::exists('natures','id')],
            'unit_symbol'=>['nullable',Rule::in(array_keys(config('actors.types')))],'type_position'=>['nullable','integer','min:0'],
            'cohesion_ids'=>['sometimes','array'],'cohesion_ids.*'=>['integer','distinct',Rule::exists('cohesions','id')->whereIn('symbol',['0:0','0:1','1:1'])],
            'participation_status'=>['sometimes',Rule::in(['pending','authorized','blocked'])],
            'application_status'=>['sometimes',Rule::in(['pending','authorized','blocked'])], 'account_user_id'=>['nullable','integer'],
        ]);
    }
    private function apply(Request $request, $system, Actor $actor, array $data): void
    {
        if(array_key_exists('name',$data)) {
            $name=filled($data['name']) ? trim($data['name']) : null;
            $actor->name=$name===$actor->identity->display_name ? null : $name;
        }
        if (!empty($data['unit_symbol'])) {
            $this->access->require($request,$system,'actors.classify');
            if(!isset($data['type_position'])) { throw ValidationException::withMessages(['type_position'=>'Seleccione el tipo.']); }
            $this->registry->classify($system,$actor,$data['unit_symbol'],(int)$data['type_position']);
        }
        if(array_key_exists('participation_status',$data)) {
            $this->access->require($request,$system,'actors.authorize'); $actor->participation_status=$data['participation_status'];
        }
        if(array_key_exists('application_status',$data) || array_key_exists('account_user_id',$data)) {
            $this->access->require($request,$system,'actors.access');
            $account=array_key_exists('account_user_id',$data) ? $data['account_user_id'] : $actor->account_user_id;
            if($account && !$system->users()->whereKey($account)->exists()) { throw ValidationException::withMessages(['account_user_id'=>'La cuenta debe pertenecer a este sistema.']); }
            if(($data['application_status'] ?? $actor->application_status)==='authorized' && !$account) { throw ValidationException::withMessages(['account_user_id'=>'Seleccione la cuenta autorizada.']); }
            $actor->account_user_id=$account;
            $actor->application_status=$data['application_status'] ?? $actor->application_status ?? 'pending';
        }
    }
    private function syncCohesions(Request $request, $system, Actor $actor, array $data): void
    {
        if($request->has('cohesions_present') || array_key_exists('cohesion_ids',$data)) {
            $this->access->require($request,$system,'actors.interactions');
            $ids=$data['cohesion_ids'] ?? [];
            if(!$request->isMethod('PUT') && !$request->isMethod('PATCH') && !$ids) { throw ValidationException::withMessages(['cohesion_ids'=>'Seleccione al menos una cohesión con el sistema.']); }
            $actor->cohesions()->sync($ids);
            $actor->classifications()->whereNotNull('cohesion_id')->whereNotIn('cohesion_id',$ids)->update(['cohesion_id'=>null]);
            $this->registry->audit($system->id,$request->user()->id,$actor->id,'interactions',['cohesion_ids'=>$ids]);
        }
    }
    public function show(Request $request, Actor $actor)
    {
        $system=$this->access->actor($request,$actor,'actors.view');
        $actor->load(['identity.entity','cohesions','children.identity.entity','parents.identity.entity','classifications.cohesion','classifications.children.actor.identity.entity','classifications.parents.actor.identity.entity','originActor.system.identity.entity']);
        $privateIdentity=$this->access->privateIdentity($request,$system,$actor->identity);
        $classCandidates=\App\Models\ActorClassification::where('system_id',$system->id)->whereHas('actor',fn($q)=>$q->whereNull('archived_at'))->with('actor.identity.entity')->orderBy('code')->get();
        $access=$this->access;
        return view('admin.actors.show',compact('actor','system','privateIdentity','access','classCandidates'));
    }
    private function resolveIdentity(Request $request, $system, array $data): \App\Models\Identity
    {
        if(!empty($data['identity_id'])) { return $this->access->identities($request,$system)->whereKey($data['identity_id'])->firstOrFail(); }
        $name=trim($data['name'] ?? '');
        if(!$name) { throw ValidationException::withMessages(['name'=>'Ingrese el nombre del actor.']); }
        $matches=collect();
        if(!$request->boolean('new_identity') && !$request->filled('identifier_number')) {
            $matches=$this->access->identities($request,$system)->with('entity')->get()->filter(fn($i)=>$i->display_name===$name || $i->entity->name===$name);
        }

        if($matches->count()===1) { return $matches->first(); }
        if($matches->count()>1) { throw ValidationException::withMessages(['identity_id'=>'Hay varias identidades con ese nombre. Seleccione la correspondiente.']); }
        $this->access->require($request,$system,'identities.manage');
        $formal=app(\App\Services\MeWo\IdentityDetails::class)->validate($request);
        $entity=\App\Models\Entity::where('name',$name)->lockForUpdate()->first();
        if($entity && $entity->identities()->exists()) {
            if(!$request->boolean('new_identity') && empty($formal['identifier_number'])) { throw ValidationException::withMessages(['name'=>'La identidad ya está registrada fuera de su alcance. Utilice Importar actor.']); }
            $entity=null; // Distinct people may share a name; identity is not determined by name alone.
        }
        if(!$entity) {
            $natureId=$data['nature_id'] ?? null;
            if(!$natureId) { throw ValidationException::withMessages(['nature_id'=>'Seleccione la naturaleza de la nueva identidad.']); }
            $entity=\App\Models\Entity::create(['name'=>$name,'code'=>'','nature_id'=>$natureId]);
        }
        return \App\Models\Identity::create($formal+['user_id'=>$request->user()->id,'entity_id'=>$entity->id]);
    }
    public function classificationStore(Request $request, Actor $actor)
    {
        $system=$this->access->actor($request,$actor,'actors.classify');
        abort_if(!$actor->system_id || $actor->archived_at,409);
        $data=$request->validate(['unit_symbol'=>['required',Rule::in(array_keys(config('actors.types')))],'type_position'=>['required','integer','min:0'],'cohesion_id'=>['nullable','integer']]);
        DB::transaction(function() use($request,$actor,$system,$data) {
            $classification=$this->registry->addClassification($system,$actor,$data['unit_symbol'],(int)$data['type_position'],empty($data['cohesion_id']) ? null : (int)$data['cohesion_id']);
            $this->registry->audit($system->id,$request->user()->id,$actor->id,'classification',['code'=>$classification->code,'cohesion_id'=>$classification->cohesion_id]);
        });
        return redirect()->route('admin.actors.show',$actor)->with('info','Clasificación SyQuAc registrada.');
    }
    public function classificationLinks(Request $request, \App\Models\ActorClassification $classification)
    {
        $system=$this->access->actor($request,$classification->actor,'actors.link');
        abort_unless((int)$classification->system_id===$system->id && !$classification->actor->archived_at,404);
        $data=$request->validate(['child_ids'=>['nullable','array'],'child_ids.*'=>['integer','distinct']]);
        DB::transaction(function() use($request,$classification,$system,$data) {
            \App\Models\System::whereKey($system->id)->lockForUpdate()->firstOrFail();
            $classification->actor->refresh(); abort_if($classification->actor->archived_at,409);
            $ids=$data['child_ids'] ?? [];
            $valid=\App\Models\ActorClassification::where('system_id',$system->id)->whereIn('id',$ids)->whereIn('unit_symbol',config('actors.links.'.$classification->unit_symbol,[]))->whereHas('actor',fn($q)=>$q->whereNull('archived_at'))->count();
            if($valid!==count($ids)) { throw ValidationException::withMessages(['child_ids'=>'Los vínculos deben respetar las unidades permitidas del mismo sistema.']); }
            $classification->children()->sync($ids);
            $this->registry->audit($system->id,$request->user()->id,$classification->actor_id,'classification.links',['classification_id'=>$classification->id,'child_ids'=>$ids]);
        });
        return back()->with('info','Vínculos de unidades actualizados.');
    }
    public function links(Request $request, Actor $actor)
    {
        $system=$this->access->actor($request,$actor,'actors.link');
        $data=$request->validate(['child_ids'=>['nullable','array'],'child_ids.*'=>['integer','distinct']]);
        DB::transaction(function() use($request,$system,$actor,$data) {
            // Serialise local relationship edits.
            \App\Models\System::whereKey($system->id)->lockForUpdate()->firstOrFail();
            $actor->refresh(); abort_if($actor->archived_at || !$actor->system_id,409);
            $ids=$data['child_ids'] ?? [];
            $valid=Actor::where('system_id',$system->id)->whereNull('archived_at')->whereIn('id',$ids)
                ->whereIn('unit_symbol',config('actors.links.'.$actor->unit_symbol,[]))->pluck('id');
            if($valid->count()!==count($ids)) { throw ValidationException::withMessages(['child_ids'=>'Los vínculos deben respetar las unidades permitidas y pertenecer al mismo sistema.']); }
            $actor->children()->sync($ids);
            $this->registry->audit($system->id,$request->user()->id,$actor->id,'links',['child_ids'=>$ids]);
        });
        return back()->with('info','Vínculos actualizados.');
    }
    public function destroy(Request $request, Actor $actor)
    {
        $system=$this->access->actor($request,$actor,'actors.archive');
        abort_unless($actor->system_id,409,'Asigne primero el sistema del actor anterior.');
        DB::transaction(function() use($request,$system,$actor) {
            \App\Models\System::whereKey($system->id)->lockForUpdate()->firstOrFail();
            $actor->update(['archived_at'=>now(),'participation_status'=>'blocked','application_status'=>'blocked']);
            $this->registry->refreshAccount($system->id,$actor->account_user_id);
            $this->registry->audit($system->id,$request->user()->id,$actor->id,'archived',[]);
        });
        return redirect()->route('admin.actors.index')->with('info','Actor archivado; se conservan sus referencias y código.');
    }
}
