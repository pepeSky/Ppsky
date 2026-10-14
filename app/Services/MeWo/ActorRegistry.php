<?php
namespace App\Services\MeWo;

use App\Models\Actor;
use App\Models\System;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ActorRegistry
{
    public function classify(System $system, Actor $actor, string $symbol, int $position): void
    {
        if (!array_key_exists($position,config('actors.types.'.$symbol,[]))) {
            throw ValidationException::withMessages(['type_position'=>'Tipo no válido para la unidad.']);
        }
        if ($actor->code) {
            if ($actor->unit_symbol!==$symbol || (int)$actor->type_position!==$position) {
                throw ValidationException::withMessages(['unit_symbol'=>'El código asignado es permanente. Registre otra participación para una clasificación distinta.']);
            }
            return;
        }
        // The owning system row serialises concurrent number allocations for all its series.
        System::whereKey($system->id)->lockForUpdate()->firstOrFail();
        $key=['system_id'=>$system->id,'unit_symbol'=>$symbol,'type_position'=>$position];
        $series=DB::table('actor_sequences')->where($key)->first();
        $number=($series?->last_number ?? 0)+1;
        DB::table('actor_sequences')->updateOrInsert($key,['last_number'=>$number]);
        $unit=\App\Models\Unit::whereIn('symbol',$symbol==='UnNE' ? ['UnNE','UnNe'] : [$symbol])->where(fn($q)=>$q->where('system','SyQuAc')->orWhereNull('system'))->firstOrFail();
        $actor->fill($key+['unit_id'=>$unit->id,'sequence'=>$number,'code'=>$symbol.' '.$position.'.'.str_pad((string)$number,3,'0',STR_PAD_LEFT)]);
    }
    public function preservePrimary(Actor $actor): void
    {
        if (!$actor->code || !$actor->system_id) { return; }
        \App\Models\ActorClassification::firstOrCreate(['actor_id'=>$actor->id,'unit_symbol'=>$actor->unit_symbol,'type_position'=>$actor->type_position],
            ['system_id'=>$actor->system_id,'unit_id'=>$actor->unit_id,'sequence'=>$actor->sequence,'code'=>$actor->code]);
    }
    public function addClassification(System $system, Actor $actor, string $symbol, int $position, ?int $cohesionId): \App\Models\ActorClassification
    {
        System::whereKey($system->id)->lockForUpdate()->firstOrFail();
        $actor->refresh();
        abort_if($actor->archived_at || (int)$actor->system_id!==$system->id,409);
        if (!array_key_exists($position,config('actors.types.'.$symbol,[]))) { throw ValidationException::withMessages(['type_position'=>'Tipo no válido para la unidad.']); }
        if ($cohesionId && !$actor->cohesions()->whereKey($cohesionId)->exists()) { throw ValidationException::withMessages(['cohesion_id'=>'Seleccione una cohesión que este actor mantenga con el sistema.']); }
        $this->preservePrimary($actor);
        $existing=$actor->classifications()->where('unit_symbol',$symbol)->where('type_position',$position)->first();
        if ($existing) {
            $existing->update(['cohesion_id'=>$cohesionId]); return $existing;
        }
        if (!$actor->code) {
            $this->classify($system,$actor,$symbol,$position); $actor->save(); $this->preservePrimary($actor);
            $classification=$actor->classifications()->where('code',$actor->code)->firstOrFail();
            $classification->update(['cohesion_id'=>$cohesionId]); return $classification;
        }
        $key=['system_id'=>$system->id,'unit_symbol'=>$symbol,'type_position'=>$position];
        $series=DB::table('actor_sequences')->where($key)->first();
        $number=($series?->last_number ?? 0)+1;
        DB::table('actor_sequences')->updateOrInsert($key,['last_number'=>$number]);
        $unit=\App\Models\Unit::whereIn('symbol',$symbol==='UnNE' ? ['UnNE','UnNe'] : [$symbol])->where(fn($q)=>$q->where('system','SyQuAc')->orWhereNull('system'))->firstOrFail();
        return $actor->classifications()->create($key+['unit_id'=>$unit->id,'cohesion_id'=>$cohesionId,'sequence'=>$number,'code'=>$symbol.' '.$position.'.'.str_pad((string)$number,3,'0',STR_PAD_LEFT)]);
    }
    public function refreshAccount(int $systemId, ?int $userId): void
    {
        if (!$userId) { return; }
        $actors=Actor::where('system_id',$systemId)->where('account_user_id',$userId)->whereNull('archived_at')->get();
        $status=$actors->contains('application_status','authorized') ? 'authorized' : ($actors->contains('application_status','pending') ? 'pending' : 'blocked');
        // Keep a revocation after unlinking an account; absence of an actor must not restore access.
        DB::table('system_user')->where('system_id',$systemId)->where('user_id',$userId)->update(['application_status'=>$status,'updated_at'=>now()]);
    }
    public function audit(int $systemId, int $userId, ?int $actorId, string $action, array $changes): void
    {
        DB::table('actor_changes')->insert(['system_id'=>$systemId,'user_id'=>$userId,'actor_id'=>$actorId,'action'=>$action,
            'changes'=>json_encode($changes,JSON_UNESCAPED_UNICODE),'created_at'=>now(),'updated_at'=>now()]);
    }
}
