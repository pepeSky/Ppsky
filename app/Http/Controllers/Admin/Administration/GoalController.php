<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Plan;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function create(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        return view(
            'admin.administration.planning.goals.create',
            compact('plan')
        );
    }

    public function store(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $plan->goals()->create($data);

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Meta creada correctamente.');
    }

    public function edit(Request $request, Plan $plan, Goal $goal)
    {
        $this->authorizeGoal($request, $plan, $goal);

        return view(
            'admin.administration.planning.goals.edit',
            compact('plan', 'goal')
        );
    }

    public function update(
        Request $request,
        Plan $plan,
        Goal $goal
    ) {
        $this->authorizeGoal($request, $plan, $goal);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $goal->update($data);

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Meta actualizada correctamente.');
    }

    public function destroy(
        Request $request,
        Plan $plan,
        Goal $goal
    ) {
        $this->authorizeGoal($request, $plan, $goal);

        if ($goal->objetives()->exists()) {
            return redirect()
                ->route('admin.planning.show', $plan)
                ->withErrors([
                    'goal' => 'La Meta no puede eliminarse mientras tenga Objetivos asociados.',
                ]);
        }

        $goal->delete();

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Meta eliminada correctamente.');
    }

    private function authorizePlan(Request $request, Plan $plan): void
    {
        $authorized = $request->user()
            ->systems()
            ->whereKey($plan->project->system_id)
            ->exists();

        abort_unless($authorized, 404);
    }

    private function authorizeGoal(
        Request $request,
        Plan $plan,
        Goal $goal
    ): void {
        $this->authorizePlan($request, $plan);

        abort_unless($goal->plan_id === $plan->id, 404);
    }
}
