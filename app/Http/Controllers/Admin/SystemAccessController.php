<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemRole;
use App\Services\MeWo\ActorAccess;
use App\Services\MeWo\ActorRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SystemAccessController extends Controller
{
    public function __construct(private ActorAccess $access, private ActorRegistry $registry) {}
    public function index(Request $request)
    {
        $system=$this->access->system($request); $this->access->require($request,$system,'access.manage');
        $roles=SystemRole::where('system_id',$system->id)->with('users')->orderBy('name')->get();
        $members=$system->users()->get(); $permissions=config('actors.permissions');
        return view('admin.actors.access',compact('system','roles','members','permissions'));
    }
    public function store(Request $request)
    {
        $system=$this->access->system($request); $this->access->require($request,$system,'access.manage');
        $data=$request->validate(['name'=>['required','string','max:100',Rule::unique('system_roles','name')->where('system_id',$system->id)],
            'permissions'=>['nullable','array'],'permissions.*'=>[Rule::in(array_keys(config('actors.permissions'))),'distinct']]);
        DB::transaction(function() use($request,$system,$data) {
            $role=SystemRole::create(['system_id'=>$system->id,'name'=>$data['name'],'permissions'=>$data['permissions'] ?? []]);
            $this->registry->audit($system->id,$request->user()->id,null,'role.created',['role_id'=>$role->id,'permissions'=>$role->permissions]);
        });
        return back()->with('info','Rol creado.');
    }
    public function update(Request $request, SystemRole $role)
    {
        $system=$this->access->system($request); $this->access->require($request,$system,'access.manage');
        abort_unless($role->system_id===$system->id,404);
        $data=$request->validate(['permissions'=>['nullable','array'],'permissions.*'=>[Rule::in(array_keys(config('actors.permissions'))),'distinct'],
            'user_ids'=>['nullable','array'],'user_ids.*'=>['integer','distinct']]);
        $ids=$data['user_ids'] ?? [];
        if($system->users()->whereIn('users.id',$ids)->count()!==count($ids)) { throw ValidationException::withMessages(['user_ids'=>'Todos los usuarios deben pertenecer a este sistema.']); }
        DB::transaction(function() use($request,$system,$role,$data,$ids) {
            $role->update(['permissions'=>$data['permissions'] ?? []]); $role->users()->sync($ids);
            $this->registry->audit($system->id,$request->user()->id,null,'role.updated',['role_id'=>$role->id,'permissions'=>$role->permissions,'user_ids'=>$ids]);
        });
        return back()->with('info','Permisos y asignaciones actualizados.');
    }
}
