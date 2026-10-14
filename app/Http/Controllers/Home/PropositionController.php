<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Proposition;
use App\Models\Binnacle;
use App\Services\MeWo\BinnacleAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PropositionController extends Controller
{
    public function index(Request $request)
    {
        $actor = $this->authenticatedActor($request);

        $propositions = Proposition::query()
            ->where('actor_id', $actor->id)
            ->latest()
            ->paginate(25);

        return view('home.propositions.index', compact(
            'actor',
            'propositions'
        ));
    }

    public function create(Request $request)
    {
        $actor = $this->authenticatedActor($request);

        [$binnacle, $activeSystem] = $this->context($request);
        return view('home.propositions.create', compact('actor', 'binnacle', 'activeSystem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sentence' => ['required', 'string', 'regex:/\S/u'],
        ]);

        $actor = $this->authenticatedActor($request);

        [$binnacle, $activeSystem] = $this->context($request);
        DB::transaction(function () use ($actor, $validated, $binnacle, $activeSystem) {
            $proposition = $actor->propositions()->create(['sentence' => trim($validated['sentence'])]);
            if ($activeSystem) {
                $proposition->systems()->attach($activeSystem->id);
            }
            $binnacle?->propositions()->attach($proposition);
        });
        if ($binnacle?->daily_date) {
            return redirect()->route('admin.posts.index', ['date' => $binnacle->daily_date])->with('info', 'Proposición registrada.');
        }
        if ($binnacle || $activeSystem) {
            return redirect()->route($binnacle ? 'admin.binnacles.show' : 'admin.posts.index', $binnacle ? [$binnacle] : [])
                ->with('info', 'Proposición registrada.');
        }

        return redirect()
            ->route('propositions.index')
            ->with('status', 'Proposición registrada.');
    }

    private function context(Request $request): array
    {
        $data = $request->validate(['binnacle_id' => ['nullable', 'integer'], 'system_id' => ['nullable', 'integer']]);
        $access = app(BinnacleAccess::class);
        $binnacle = empty($data['binnacle_id']) ? null : Binnacle::findOrFail($data['binnacle_id']);
        if ($binnacle) {
            $access->check($request, $binnacle);
            abort_if(! empty($data['system_id']) && (int) $data['system_id'] !== $binnacle->system_id, 422);
            return [$binnacle, $binnacle->system];
        }
        return [null, empty($data['system_id']) ? null : $access->system($request, (int) $data['system_id'])];
    }

    private function authenticatedActor(Request $request): Actor
    {
        $binnacleId=$request->input('binnacle_id');
        $context=$binnacleId ? Binnacle::findOrFail($binnacleId)->system_id : $request->input('system_id');
        $system=app(BinnacleAccess::class)->system($request,$context ? (int)$context : null);
        return Actor::query()
            ->whereNull('archived_at')
            ->where(fn($q)=>$q->where('system_id',$system?->id)->orWhereNull('system_id'))
            ->orderByRaw('CASE WHEN system_id IS NULL THEN 1 ELSE 0 END')
            ->orderBy('id')
            ->whereHas('identity', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->whereHas('identity.entity', function ($query) use ($request) {
                $query->where('name', $request->user()->name);
            })
            ->firstOrFail();
    }
}
