<?php
namespace App\Services\MeWo;

use App\Models\Actor;
use App\Models\Identity;
use App\Models\System;
use App\Models\SystemRole;
use Illuminate\Http\Request;

class ActorAccess
{
    public function system(Request $request): System
    {
        return app(BinnacleAccess::class)->system($request) ?? abort(403, 'Seleccione un sistema al que pertenezca.');
    }
    public function allows(Request $request, System $system, string $permission): bool
    {
        if (!$this->canEnter($request->user(),$system)) { return false; }
        if ((int) $system->identity->user_id === (int) $request->user()->id) { return true; }
        return SystemRole::where('system_id',$system->id)->whereHas('users',fn($q)=>$q->where('users.id',$request->user()->id))
            ->get()->contains(fn($role)=>in_array($permission,$role->permissions ?? [],true));
    }
    public function canEnter($user, System $system): bool
    {
        $membership=$system->users()->withPivot('application_status')->whereKey($user->id)->first();
        if (!$membership) { return false; }
        if ((int)$system->identity->user_id===(int)$user->id) { return true; }
        if ($membership->pivot->application_status && $membership->pivot->application_status!=='authorized') { return false; }
        $accounts=Actor::where('system_id',$system->id)->where('account_user_id',$user->id)->get();
        return $accounts->isEmpty() || $accounts->contains(fn($a)=>!$a->archived_at && $a->application_status==='authorized');
    }
    public function require(Request $request, System $system, string $permission): void
    {
        abort_unless($this->allows($request,$system,$permission),403);
    }
    public function actor(Request $request, Actor $actor, string $permission): System
    {
        $system=$this->system($request);
        abort_unless((int)$actor->system_id===$system->id || (!$actor->system_id && (int)$actor->identity->user_id===(int)$request->user()->id),404);
        $this->require($request,$system,$permission);
        return $system;
    }
    public function identities(Request $request, System $system)
    {
        return Identity::where(fn($q)=>$q->where('user_id',$request->user()->id)->orWhereHas('actors',fn($a)=>$a->where('system_id',$system->id)->whereNull('origin_actor_id')));
    }
    public function privateIdentity(Request $request, System $system, Identity $identity): bool
    {
        return $this->allows($request,$system,'identities.view') && $this->identities($request,$system)->where('identities.id',$identity->id)->exists();
    }
}
