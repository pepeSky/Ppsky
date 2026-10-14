<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Binnacle;
use App\Models\Post;
use App\Models\Proposition;
use App\Services\MeWo\BinnacleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BinnacleController extends Controller
{
    public function __construct(private BinnacleAccess $access) {}

    public function index(Request $request)
    {
        $systems = $request->user()->systems()->with('identity.entity')->orderBy('systems.id')->get();
        $activeSystem = $this->access->system($request);
        $binnacles = Binnacle::with(['system.identity.entity', 'project', 'user'])
            ->withCount(['propositions', 'activities', 'models'])
            ->where('system_id', $activeSystem?->id ?? 0)
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderByDesc('recorded_on')->orderByDesc('id')->paginate(20)->withQueryString();
        $legacyPosts = collect();
        return view('admin.binnacle.index', compact('systems', 'activeSystem', 'binnacles', 'legacyPosts'));
    }

    public function create(Request $request)
    {
        $activeSystem = $this->access->system($request);
        abort_unless($activeSystem, 404, 'No hay un sistema disponible.');
        return view('admin.binnacle.record-form', $this->formData($request, $activeSystem));
    }

    public function store(Request $request)
    {
        $request->validate(['system_id' => ['required', 'integer']]);
        $system = $this->access->system($request, (int) $request->input('system_id'));
        $data = $this->validated($request, $system);
        $binnacle = DB::transaction(function () use ($request, $system, $data) {
            $binnacle = Binnacle::create([
                'system_id' => $system->id, 'user_id' => $request->user()->id,
                'project_id' => $data['project_id'] ?? null, 'recorded_on' => $data['recorded_on'],
                'title' => $data['title'], 'summary' => $data['summary'] ?? null,
            ]);
            $this->link($binnacle, $data);
            return $binnacle;
        });
        return redirect()->route('admin.binnacles.show', $binnacle)->with('info', 'Bitácora creada.');
    }

    public function show(Request $request, Binnacle $binnacle)
    {
        $this->access->check($request, $binnacle);
        $binnacle->load(['system.identity.entity', 'project', 'user', 'propositions.actor.identity.entity', 'models', 'activities.tasks.context']);
        return view('admin.binnacle.record-show', compact('binnacle'));
    }

    public function edit(Request $request, Binnacle $binnacle)
    {
        $this->access->check($request, $binnacle);
        $binnacle->load(['propositions', 'models', 'activities']);
        return view('admin.binnacle.record-form', $this->formData($request, $binnacle->system, $binnacle));
    }

    public function update(Request $request, Binnacle $binnacle)
    {
        $this->access->check($request, $binnacle);
        $data = $this->validated($request, $binnacle->system);
        if ($binnacle->activities()->exists() && (int) ($data['project_id'] ?? 0) !== (int) $binnacle->project_id) {
            throw \Illuminate\Validation\ValidationException::withMessages(['project_id' => 'Conserve el proyecto de las actividades ya registradas.']);
        }
        DB::transaction(function () use ($binnacle, $data) {
            $binnacle->update(collect($data)->only(['project_id', 'recorded_on', 'title', 'summary'])->all());
            $this->link($binnacle, $data);
        });
        return redirect()->route('admin.binnacles.show', $binnacle)->with('info', 'Bitácora actualizada.');
    }

    private function formData(Request $request, $system, ?Binnacle $binnacle = null): array
    {
        return [
            'activeSystem' => $system, 'binnacle' => $binnacle,
            'projects' => $system->projects()->orderBy('name')->get(),
            'propositions' => $this->access->propositions($request, $system->id)->latest()->get(),
            'models' => $request->user()->models()->orderBy('name')->get(),
            'activities' => Activity::whereHas('project', fn ($query) => $query->where('system_id', $system->id))->with('project')->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, $system): array
    {
        return $request->validate([
            'recorded_on' => ['required', 'date_format:Y-m-d'], 'title' => ['required', 'string', 'max:255', 'regex:/\S/u'],
            'summary' => ['nullable', 'string'],
            'project_id' => ['nullable', 'integer', Rule::in($system->projects()->pluck('id')->all())],
            'proposition_ids' => ['sometimes', 'array'],
            'proposition_ids.*' => ['integer', 'distinct', Rule::in($this->access->propositions($request, $system->id)->pluck('id')->all())],
            'model_ids' => ['sometimes', 'array'],
            'model_ids.*' => ['integer', 'distinct', Rule::in($request->user()->models()->pluck('id')->all())],
            'activity_ids' => ['sometimes', 'array'],
            'activity_ids.*' => ['integer', 'distinct', Rule::in(Activity::whereHas('project', fn ($query) => $query->where('system_id', $system->id))
                ->when($request->filled('project_id'), fn ($query) => $query->where('project_id', $request->input('project_id')))->pluck('id')->all())],
        ]);
    }

    private function link(Binnacle $binnacle, array $data): void
    {
        foreach ($data['proposition_ids'] ?? [] as $id) {
            Proposition::findOrFail($id)->systems()->syncWithoutDetaching([$binnacle->system_id]);
        }
        // Adding records never silently discards previously included history.
        $binnacle->propositions()->syncWithoutDetaching($data['proposition_ids'] ?? []);
        $binnacle->models()->syncWithoutDetaching($data['model_ids'] ?? []);
        $binnacle->activities()->syncWithoutDetaching($data['activity_ids'] ?? []);
    }
}
