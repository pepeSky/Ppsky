<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityProgram;
use App\Models\InteractionPeriod;
use App\Services\MeWo\BinnacleAccess;
use App\Services\MeWo\DailyBinnacle;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DailyBinnacleController extends Controller
{
    public function __construct(private BinnacleAccess $access, private DailyBinnacle $daily) {}

    public function index(Request $request)
    {
        $data = $request->validate(['date' => ['nullable', 'date_format:Y-m-d']]);
        $date = $data['date'] ?? CarbonImmutable::now(DailyBinnacle::TIMEZONE)->toDateString();
        $systems = $request->user()->systems()->with('identity.entity')->orderBy('systems.id')->get();
        $activeSystem = $this->access->system($request);
        $binnacle = $activeSystem ? $this->daily->ensure($activeSystem, $request->user()->id, $date) : null;
        [$programmed, $unprogrammed] = $activeSystem ? $this->daily->activities($activeSystem, $request->user()->id, $date) : [collect(), collect()];
        $indicators = $this->daily->indicators($activeSystem?->id ?? 0, $request->user()->id, $date);
        $propositions = $binnacle ? $binnacle->propositions()->with('actor.identity.entity')->latest()->get() : collect();
        $interactions = $this->daily->interactions($request);
        return view('admin.binnacle.daily', compact('systems', 'activeSystem', 'binnacle', 'date', 'programmed', 'unprogrammed', 'indicators', 'propositions', 'interactions'));
    }

    public function program(Request $request, Activity $activity)
    {
        $data = $request->validate(['date' => ['required', 'date_format:Y-m-d']]);
        $system = $this->access->system($request);
        abort_unless($system && $activity->project->system_id === $system->id, 404);
        ActivityProgram::firstOrCreate(['activity_id' => $activity->id, 'user_id' => $request->user()->id, 'scheduled_on' => $data['date']]);
        $this->daily->ensure($system, $request->user()->id, $data['date']);
        return redirect()->route('admin.posts.index', ['date' => $data['date']]);
    }

    public function time(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'], 'system_id' => ['required', 'integer'],
            'interaction_id' => ['nullable', 'integer'], 'rest' => ['nullable', 'boolean'],
            'starts_at' => ['required', 'date_format:Y-m-d\TH:i'], 'ends_at' => ['required', 'date_format:Y-m-d\TH:i', 'after:starts_at'],
        ]);
        $system = $this->access->system($request, (int) $data['system_id']);
        $start = CarbonImmutable::parse($data['starts_at'], DailyBinnacle::TIMEZONE)->utc();
        $end = CarbonImmutable::parse($data['ends_at'], DailyBinnacle::TIMEZONE)->utc();
        if ($request->boolean('rest') && ! empty($data['interaction_id'])) {
            throw ValidationException::withMessages(['interaction_id' => 'Registre una interacción o descanso, por separado.']);
        }
        $interaction = $request->boolean('rest') ? null : $this->daily->interactions($request)->firstWhere('id', (int) ($data['interaction_id'] ?? 0));
        $category = $request->boolean('rest') ? 'rest' : ($interaction ? $this->daily->category($interaction) : null);
        if (! $category) {
            throw ValidationException::withMessages(['interaction_id' => 'Seleccione una interacción con cohesión Existencial, Ocio o Negocio.']);
        }
        DB::transaction(function () use ($request, $data, $system, $start, $end, $interaction, $category) {
            // Serialise this user's entries; time cannot be counted twice across systems.
            $request->user()->newQuery()->whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if (InteractionPeriod::where('user_id', $request->user()->id)->where('starts_at', '<', $end)->where('ends_at', '>', $start)->exists()) {
                throw ValidationException::withMessages(['starts_at' => 'Ese intervalo se superpone con tiempo ya registrado.']);
            }
            InteractionPeriod::create(['user_id' => $request->user()->id, 'system_id' => $system->id,
                'interaction_id' => $interaction?->id, 'category' => $category, 'starts_at' => $start, 'ends_at' => $end]);
        });
        return redirect()->route('admin.posts.index', ['date' => $data['date']])->with('info', 'Tiempo registrado.');
    }
}
