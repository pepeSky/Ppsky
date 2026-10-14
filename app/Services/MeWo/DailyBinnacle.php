<?php

namespace App\Services\MeWo;

use App\Models\Activity;
use App\Models\ActivityProgram;
use App\Models\Binnacle;
use App\Models\Interaction;
use App\Models\InteractionPeriod;
use App\Models\Proposition;
use App\Models\System;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyBinnacle
{
    public const TIMEZONE = 'America/Santiago';

    public function bounds(string $date): array
    {
        $start = CarbonImmutable::parse($date, self::TIMEZONE)->startOfDay();
        return [$start->utc(), $start->addDay()->utc()];
    }

    public function ensure(System $system, int $userId, string $date): Binnacle
    {
        // Database uniqueness prevents concurrent visits from creating two daily records.
        $binnacle = Binnacle::firstOrCreate(['system_id' => $system->id, 'daily_date' => $date], [
            'user_id' => $userId, 'recorded_on' => $date,
            'title' => 'Bitácora '.$date,
        ]);
        $programs = ActivityProgram::where('scheduled_on', $date)->where('user_id', $userId)
            ->whereHas('activity.project', fn ($q) => $q->where('system_id', $system->id))->pluck('activity_id');
        $binnacle->activities()->syncWithoutDetaching($programs->all());
        [$start, $end] = $this->bounds($date);
        $propositions = Proposition::where('created_at', '>=', $start)->where('created_at', '<', $end)
            ->whereHas('actor.identity', fn ($q) => $q->where('user_id', $userId))
            ->where(fn ($q) => $q->whereHas('systems', fn ($s) => $s->where('systems.id', $system->id))
                ->orWhere(fn ($unassigned) => $unassigned->whereDoesntHave('systems')->whereHas('actor', fn ($a) => $a->where('identity_id', $system->identity_id))))
            ->pluck('id');
        $binnacle->propositions()->syncWithoutDetaching($propositions->all());
        return $binnacle;
    }

    public function activities(System $system, int $userId, string $date): array
    {
        $programmedIds = ActivityProgram::where('scheduled_on', $date)->where('user_id', $userId)->pluck('activity_id');
        $activities = Activity::whereHas('project', fn ($q) => $q->where('system_id', $system->id))->with(['project', 'tasks'])->orderBy('name')->get();
        return [
            $activities->filter(fn ($activity) => $programmedIds->contains($activity->id)),
            $activities->reject(fn ($activity) => $programmedIds->contains($activity->id)),
        ];
    }

    public function interactions(Request $request)
    {
        return app(InteractionAccess::class)->query($request)->with(['cohesion','actorA.identity.entity','actorB.identity.entity'])
            ->whereHas('actorA',fn($a)=>$a->whereNull('archived_at')->where(fn($q)=>$q->whereNull('system_id')->orWhere('participation_status','authorized')))
            ->whereHas('actorB',fn($a)=>$a->whereNull('archived_at')->where(fn($q)=>$q->whereNull('system_id')->orWhere('participation_status','authorized')))->get();
    }

    public function indicators(int $systemId, int $userId, string $date): array
    {
        [$start, $end] = $this->bounds($date);
        $seconds = array_fill_keys(['existential', 'leisure', 'business', 'rest'], 0);
        $periods = InteractionPeriod::where('system_id', $systemId)->where('user_id', $userId)
            ->where('starts_at', '<', $end)->where('ends_at', '>', $start)->get();
        foreach ($periods as $period) {
            if (! array_key_exists($period->category, $seconds)) { continue; }
            $seconds[$period->category] += max(0, min($end->timestamp, CarbonImmutable::parse($period->getRawOriginal('ends_at'), 'UTC')->timestamp) - max($start->timestamp, CarbonImmutable::parse($period->getRawOriginal('starts_at'), 'UTC')->timestamp));
        }
        $total = $end->timestamp - $start->timestamp;
        return ['seconds' => $seconds, 'day_seconds' => $total, 'recorded_seconds' => array_sum($seconds),
            'percentages' => array_map(fn ($value) => round(100 * $value / $total, 1), $seconds)];
    }

    public function category(Interaction $interaction): ?string
    {
        return match ($interaction->cohesion->symbol) {
            '0:0' => 'existential', '0:1' => 'leisure', '1:1' => 'business', default => null,
        };
    }
}
