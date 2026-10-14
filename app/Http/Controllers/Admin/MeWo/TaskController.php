<?php

namespace App\Http\Controllers\Admin\MeWo;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Task;
use App\Models\Unit;
use App\Services\MeWo\WorkAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct(private WorkAccess $access) {}

    public function create(Request $request, Activity $activity)
    {
        $this->access->activity($request, $activity);
        return view('admin.activities.tasks.form', $this->formData($activity));
    }

    public function store(Request $request, Activity $activity)
    {
        $this->access->activity($request, $activity);
        $activity->tasks()->create($this->validated($request, $activity));
        return redirect()->route('admin.activities.show', $activity)->with('info', 'Tarea creada.');
    }

    public function edit(Request $request, Activity $activity, Task $task)
    {
        $this->check($request, $activity, $task);
        return view('admin.activities.tasks.form', $this->formData($activity, $task));
    }

    public function update(Request $request, Activity $activity, Task $task)
    {
        $this->check($request, $activity, $task);
        $task->update($this->validated($request, $activity));
        return redirect()->route('admin.activities.show', $activity)->with('info', 'Tarea actualizada.');
    }

    public function destroy(Request $request, Activity $activity, Task $task)
    {
        $this->check($request, $activity, $task);
        // Work already quantified is a historical record, not a disposable draft.
        if ($task->status !== 'pending' || (float) $task->quantity > 0) {
            return back()->withErrors(['task' => 'Solo puede eliminar tareas pendientes sin cuantificación.']);
        }
        $task->delete();
        return redirect()->route('admin.activities.show', $activity)->with('info', 'Tarea eliminada.');
    }

    private function check(Request $request, Activity $activity, Task $task): void
    {
        $this->access->activity($request, $activity);
        abort_unless($task->activity_id === $activity->id, 404);
    }

    private function formData(Activity $activity, ?Task $task = null): array
    {
        return ['activity' => $activity, 'task' => $task, 'processes' => $activity->mewoProcesses()->with('context')->get(), 'units' => Unit::orderBy('id')->get()];
    }

    private function validated(Request $request, Activity $activity): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string'],
            'mewo_process_id' => ['required', 'integer', Rule::in($activity->mewoProcesses()->pluck('mewo_processes.id')->all())],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'quantity' => ['required', 'numeric', 'min:0', 'max:9999999999.9999', 'decimal:0,4'],
            'unit_id' => ['nullable', 'required_if:status,completed', Rule::requiredIf(fn () => (float) $request->input('quantity', 0) > 0), 'integer', 'exists:units,id'],
        ]);
        $data['context_id'] = $activity->mewoProcesses()->findOrFail($data['mewo_process_id'])->context_id;
        $data['description'] = $data['description'] ?? '';
        return $data;
    }
}
