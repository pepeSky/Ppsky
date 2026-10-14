<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Objetive;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ObjetiveController extends Controller
{
    public function create(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        $parent = null;

        if ($request->filled('parent_id')) {
            $parent = $this->findParent(
                $plan,
                $request->input('parent_id')
            );

            abort_unless($parent !== null, 404);
            abort_unless($this->depth($parent) < 2, 422);
        }

        $goals = $plan->goals()->orderBy('id')->get();

        return view(
            'admin.administration.planning.objetives.create',
            compact('plan', 'parent', 'goals')
        );
    }

    public function store(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        $data = $request->validate([
            'description' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer'],
            'goal_id' => ['nullable', 'integer'],
        ]);

        $parent = null;
        $goalId = null;

        if (!empty($data['parent_id'])) {
            $parent = $this->findParent(
                $plan,
                $data['parent_id']
            );

            if ($parent === null || $this->depth($parent) >= 2) {
                throw ValidationException::withMessages([
                    'parent_id' => 'El objetivo padre no es válido.',
                ]);
            }

            $goalId = $parent->goal_id;
        } elseif (!empty($data['goal_id'])) {
            $goal = $plan->goals()
                ->find($data['goal_id']);

            if ($goal === null) {
                throw ValidationException::withMessages([
                    'goal_id' => 'La Meta no pertenece al Plan.',
                ]);
            }

            $goalId = $goal->id;
        }

        $plan->objetives()->create([
            'project_id' => $plan->project_id,
            'description' => $data['description'],
            'parent_id' => $parent?->id,
            'goal_id' => $goalId,
            'is_general' => false,
        ]);

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Objetivo creado correctamente.');
    }

    public function edit(
        Request $request,
        Plan $plan,
        Objetive $objetive
    ) {
        $this->authorizeObjetive($request, $plan, $objetive);

        $goals = $plan->goals()->orderBy('id')->get();

        return view(
            'admin.administration.planning.objetives.edit',
            compact('plan', 'objetive', 'goals')
        );
    }

    public function update(
        Request $request,
        Plan $plan,
        Objetive $objetive
    ) {
        $this->authorizeObjetive($request, $plan, $objetive);

        $data = $request->validate([
            'description' => ['required', 'string'],
            'goal_id' => ['nullable', 'integer'],
        ]);

        DB::transaction(function () use ($plan, $objetive, $data) {
            $objetive->description = $data['description'];

            if (!$objetive->is_general && $objetive->parent_id === null) {
                $goalId = $data['goal_id'] ?? null;

                if ($goalId !== null) {
                    $goal = $plan->goals()->find($goalId);

                    if ($goal === null) {
                        throw ValidationException::withMessages([
                            'goal_id' => 'La Meta no pertenece al Plan.',
                        ]);
                    }
                }

                $objetive->goal_id = $goalId;
            }

            $objetive->save();

            if (!$objetive->is_general && $objetive->parent_id === null) {
                $this->propagateGoal($objetive);
            }
        });

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Objetivo actualizado correctamente.');
    }

    public function destroy(
        Request $request,
        Plan $plan,
        Objetive $objetive
    ) {
        $this->authorizeObjetive($request, $plan, $objetive);

        if ($objetive->is_general) {
            return back()->withErrors([
                'objetive' => 'El Objetivo General no puede eliminarse.',
            ]);
        }

        if (
            $objetive->children()->exists() ||
            $objetive->activities()->exists()
        ) {
            return back()->withErrors([
                'objetive' => 'El Objetivo tiene dependencias y no puede eliminarse.',
            ]);
        }

        $objetive->delete();

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Objetivo eliminado correctamente.');
    }

    private function authorizePlan(Request $request, Plan $plan): void
    {
        $authorized = $request->user()
            ->systems()
            ->whereKey($plan->project->system_id)
            ->exists();

        abort_unless($authorized, 404);
    }

    private function authorizeObjetive(
        Request $request,
        Plan $plan,
        Objetive $objetive
    ): void {
        $this->authorizePlan($request, $plan);

        abort_unless(
            $objetive->plan_id === $plan->id &&
            $objetive->project_id === $plan->project_id,
            404
        );
    }

    private function findParent(Plan $plan, int $id): ?Objetive
    {
        return $plan->objetives()
            ->where('project_id', $plan->project_id)
            ->where('is_general', false)
            ->find($id);
    }

    private function depth(Objetive $objetive): int
    {
        $depth = 0;
        $visited = [];

        while ($objetive->parent_id !== null) {
            abort_if(isset($visited[$objetive->id]), 422);

            $visited[$objetive->id] = true;
            $depth++;

            abort_if($depth > 2, 422);

            $parent = $objetive->parent;

            abort_unless(
                $parent !== null &&
                $parent->plan_id === $objetive->plan_id &&
                $parent->project_id === $objetive->project_id &&
                !$parent->is_general,
                422
            );

            $objetive = $parent;
        }

        return $depth;
    }

    private function propagateGoal(Objetive $objetive): void
    {
        foreach ($objetive->children as $child) {
            $child->goal_id = $objetive->goal_id;
            $child->save();

            $this->propagateGoal($child);
        }
    }
}
