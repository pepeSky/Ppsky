<?php
namespace App\Services\MeWo;
use App\Models\Actor;
use App\Models\Interaction;
use Illuminate\Http\Request;
class InteractionAccess
{
    public function query(Request $request)
    {
        $system=app(BinnacleAccess::class)->system($request);
        return Interaction::where(function($q) use($request,$system) {
            $q->where(fn($local)=>$local->whereHas('actorA',fn($a)=>$a->where('system_id',$system?->id ?? 0))->whereHas('actorB',fn($a)=>$a->where('system_id',$system?->id ?? 0)))
                ->orWhere(fn($old)=>$old->whereHas('actorA',fn($a)=>$a->whereNull('system_id'))->whereHas('actorB',fn($a)=>$a->whereNull('system_id'))
                    ->where(fn($own)=>$own->whereHas('actorA.identity',fn($a)=>$a->where('user_id',$request->user()->id))->orWhereHas('actorB.identity',fn($a)=>$a->where('user_id',$request->user()->id))));
        });
    }
    public function actors(Request $request)
    {
        $system=app(ActorAccess::class)->system($request);
        return Actor::where('system_id',$system->id)->whereNull('archived_at')->where('participation_status','authorized');
    }
}
