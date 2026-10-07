<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index(Request $request)
    {
        $systems = $request->user()
            ->systems()
            ->with('identity.entity')
            ->orderBy('systems.id')
            ->get();

        $activeSystem = $systems->firstWhere(
            'id',
            $request->session()->get('active_system_id')
        );

        if (! $activeSystem) {
            $activeSystem = $systems->first();

            if ($activeSystem) {
                $request->session()->put(
                    'active_system_id',
                    $activeSystem->id
                );
            }
        }

        $plans = collect();

        if ($activeSystem) {
            $plans = Plan::query()
                ->whereHas('project', function ($query) use ($activeSystem) {
                    $query->where('system_id', $activeSystem->id);
                })
                ->with([
                    'project.system.identity.entity',
                    'goals',
                    'objetives',
                ])
                ->orderBy('starts_at')
                ->get();
        }

        return view(
            'admin.administration.planning.index',
            compact('systems', 'activeSystem', 'plans')
        );
    }

    public function show(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        $plan->load([
            'project.system.identity.entity',
            'goals',
            'objetives.goal',
            'objetives.children',
        ]);

        return view(
            'admin.administration.planning.show',
            compact('plan')
        );
    }

    public function edit(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        return view(
            'admin.administration.planning.edit',
            compact('plan')
        );
    }

    public function update(Request $request, Plan $plan)
    {
        $this->authorizePlan($request, $plan);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:operational,tactical,strategic'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $plan->update($data);

        return redirect()
            ->route('admin.planning.show', $plan)
            ->with('info', 'Planificación actualizada correctamente.');
    }

    private function authorizePlan(Request $request, Plan $plan): void
    {
        $authorized = $request->user()
            ->systems()
            ->whereKey($plan->project->system_id)
            ->exists();

        abort_unless($authorized, 404);
    }
}
