<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Context;
use App\Models\MewoProcess;
use App\Services\MeWo\QuantumObjectives;
use App\Services\MeWo\WorkAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    public function __construct(private WorkAccess $access, private QuantumObjectives $objectives) {}

    public function index(Request $request)
    {
        $activities = Activity::whereIn('project_id', $this->access->projects($request)->select('projects.id'))
            ->with(['project', 'contexts', 'objetives'])->withCount('tasks')->latest()->paginate(15);
        return view('admin.activities.index', compact('activities'));
    }

    public function create(Request $request)
    {
        return view('admin.activities.form', $this->formData($request));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $activity = DB::transaction(function () use ($data) {
            $activity = Activity::create(collect($data)->only(['project_id', 'name', 'description'])->all());
            $this->sync($activity, $data);
            return $activity;
        });
        return redirect()->route('admin.activities.show', $activity)->with('info', 'Actividad creada.');
    }

    public function show(Request $request, Activity $activity)
    {
        $this->access->activity($request, $activity);
        $activity->load(['project', 'objetives', 'contexts', 'mewoProcesses.context', 'tasks.context', 'tasks.mewoProcess', 'tasks.unit']);
        $quantities = $activity->quantities();
        return view('admin.activities.show', compact('activity', 'quantities'));
    }

    public function edit(Request $request, Activity $activity)
    {
        $this->access->activity($request, $activity);
        $activity->load(['contexts', 'mewoProcesses', 'objetives']);
        return view('admin.activities.form', $this->formData($request, $activity));
    }

    public function update(Request $request, Activity $activity)
    {
        $this->access->activity($request, $activity);
        $data = $this->validateData($request, $activity);
        DB::transaction(function () use ($activity, $data) {
            $activity->update(collect($data)->only(['project_id', 'name', 'description'])->all());
            $this->sync($activity, $data);
        });
        return redirect()->route('admin.activities.show', $activity)->with('info', 'Actividad actualizada.');
    }

    public function destroy(Request $request, Activity $activity)
    {
        $this->access->activity($request, $activity);
        if ($activity->tasks()->exists()) {
            return back()->withErrors(['activity' => 'No se puede eliminar una actividad con tareas.']);
        }
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('info', 'Actividad eliminada.');
    }

    private function formData(Request $request, ?Activity $activity = null): array
    {
        $projects = $this->access->projects($request)->orderBy('name')->get();
        $objectives = $projects->flatMap(fn ($project) => $this->objectives->available($project->id));
        return [
            'activity' => $activity, 'projects' => $projects, 'objectives' => $objectives,
            'contexts' => Context::with('components')->orderBy('name')->get(),
            'processes' => MewoProcess::with('context')->orderBy('context_id')->orderBy('id')->get(),
        ];
    }

    private function validateData(Request $request, ?Activity $activity = null): array
    {
        $data = $request->validate([
            'project_id' => ['required', 'integer', Rule::in($this->access->projects($request)->pluck('id')->all())],
            'name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string'],
            'objetive_id' => ['nullable', 'integer'],
            'context_ids' => ['required', 'array', 'min:1'], 'context_ids.*' => ['integer', 'distinct', 'exists:contexts,id'],
            'process_ids' => ['required', 'array', 'min:1'], 'process_ids.*' => ['integer', 'distinct', 'exists:mewo_processes,id'],
        ]);
        if (! empty($data['objetive_id']) && ! $this->objectives->available($data['project_id'])->contains('id', (int) $data['objetive_id'])) {
            throw ValidationException::withMessages(['objetive_id' => 'Seleccione un objetivo cuántico del mismo proyecto.']);
        }
        // An expanded context grants participation to its simple components.
        $contexts = Context::with('components')->whereIn('id', $data['context_ids'])->get();
        $allowed = $contexts->flatMap(fn ($context) => $context->type === 'simple' ? [$context->id] : $context->components->pluck('id')->all())->unique();
        if ($contexts->contains(fn ($context) => $context->type === 'ampliado' && $context->components->isEmpty())) {
            throw ValidationException::withMessages(['context_ids' => 'El contexto ampliado necesita componentes definidos.']);
        }
        $processes = MewoProcess::whereIn('id', $data['process_ids'])->get();
        if ($processes->contains(fn ($process) => ! $allowed->contains($process->context_id))) {
            throw ValidationException::withMessages(['process_ids' => 'Cada proceso debe pertenecer a un contexto participante.']);
        }
        // Prevent edits from orphaning existing operational records.
        if ($activity && ($activity->tasks()->whereNotIn('context_id', $contexts->pluck('id')->merge($allowed)->unique())->exists()
            || $activity->tasks()->whereNotNull('mewo_process_id')->whereNotIn('mewo_process_id', $data['process_ids'])->exists())) {
            throw ValidationException::withMessages(['context_ids' => 'Conserve los contextos y procesos que tienen tareas registradas.']);
        }
        if ($activity && ($activity->tasks()->exists() || $activity->objetives()->exists()) && $activity->project_id !== (int) $data['project_id']) {
            throw ValidationException::withMessages(['project_id' => 'No cambie el proyecto de una actividad con tareas u objetivos vinculados.']);
        }
        $data['context_ids'] = $contexts->pluck('id')->merge($allowed)->unique()->all();
        return $data;
    }

    private function sync(Activity $activity, array $data): void
    {
        $activity->contexts()->sync($data['context_ids']);
        $activity->mewoProcesses()->sync($data['process_ids']);
        // Preserve legacy links when the published checkout lacks the user's hierarchy.
        if (! empty($data['objetive_id']) || $this->objectives->available($activity->project_id)->isNotEmpty()) {
            $activity->objetives()->sync(empty($data['objetive_id']) ? [] : [$data['objetive_id']]);
        }
    }
}
