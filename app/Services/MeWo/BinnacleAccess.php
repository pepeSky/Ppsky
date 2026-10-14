<?php

namespace App\Services\MeWo;

use App\Models\Binnacle;
use App\Models\Proposition;
use App\Models\System;
use Illuminate\Http\Request;

class BinnacleAccess
{
    public function system(Request $request, ?int $id = null): ?System
    {
        $systems = $request->user()->systems()->with('identity.entity')->orderBy('systems.id')->get()->filter(fn($system)=>app(ActorAccess::class)->canEnter($request->user(),$system));
        if ($id) {
            return $systems->firstWhere('id', $id) ?? abort(404);
        }
        $active = $systems->firstWhere('id', (int) $request->session()->get('active_system_id')) ?? $systems->first();
        if ($active) {
            $request->session()->put('active_system_id', $active->id);
        }
        return $active;
    }

    public function check(Request $request, Binnacle $binnacle): void
    {
        $this->system($request, $binnacle->system_id);
    }

    public function propositions(Request $request, int $systemId)
    {
        return Proposition::whereHas('actor.identity', fn ($query) => $query->where('user_id', $request->user()->id))
            ->where(fn ($query) => $query->whereDoesntHave('systems')
                ->orWhereHas('systems', fn ($systems) => $systems->where('systems.id', $systemId)));
    }
}
