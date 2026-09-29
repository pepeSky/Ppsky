<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Cohesion;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InteractionController extends Controller
{
    public function index()
    {
        $interactions = Interaction::with([
                'actorA.identity.entity',
                'actorB.identity.entity',
                'cohesion',
            ])
            ->orderBy('id')
            ->paginate(15);

        return view(
            'admin.interactions.index',
            compact('interactions')
        );
    }

    public function create()
    {
        $actors = Actor::with('identity.entity')
            ->orderBy('id')
            ->get();

        $cohesions = Cohesion::orderBy('id')->get();

        return view(
            'admin.interactions.create',
            compact('actors', 'cohesions')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'actor_a_id' => [
                'required',
                Rule::exists('actors', 'id'),
                'different:actor_b_id',
            ],

            'actor_b_id' => [
                'required',
                Rule::exists('actors', 'id'),
                'different:actor_a_id',
            ],

            'cohesion_id' => [
                'required',
                Rule::exists('cohesions', 'id'),
            ],
        ]);

        $actorA = min(
            (int) $data['actor_a_id'],
            (int) $data['actor_b_id']
        );

        $actorB = max(
            (int) $data['actor_a_id'],
            (int) $data['actor_b_id']
        );

        $exists = Interaction::where('actor_a_id', $actorA)
            ->where('actor_b_id', $actorB)
            ->where('cohesion_id', $data['cohesion_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'cohesion_id' =>
                        'Esta interacción ya existe para los actores y cohesión seleccionados.',
                ]);
        }

        Interaction::create([
            'actor_a_id' => $actorA,
            'actor_b_id' => $actorB,
            'cohesion_id' => $data['cohesion_id'],
        ]);

        return redirect()
            ->route('admin.interactions.index')
            ->with('info', 'Interacción registrada correctamente.');
    }

    public function show(Interaction $interaction)
    {
        $interaction->load([
            'actorA.identity.entity',
            'actorB.identity.entity',
            'cohesion',
        ]);

        return view(
            'admin.interactions.show',
            compact('interaction')
        );
    }

    public function edit(Interaction $interaction)
    {
        $interaction->load([
            'actorA.identity.entity',
            'actorB.identity.entity',
        ]);

        $cohesions = Cohesion::orderBy('id')->get();

        return view(
            'admin.interactions.edit',
            compact('interaction', 'cohesions')
        );
    }

    public function update(
        Request $request,
        Interaction $interaction
    ) {
        $data = $request->validate([
            'cohesion_id' => [
                'required',
                Rule::exists('cohesions', 'id'),
            ],
        ]);

        $duplicate = Interaction::where(
                'actor_a_id',
                $interaction->actor_a_id
            )
            ->where(
                'actor_b_id',
                $interaction->actor_b_id
            )
            ->where(
                'cohesion_id',
                $data['cohesion_id']
            )
            ->where('id', '!=', $interaction->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'cohesion_id' =>
                        'Esta interacción ya existe para los actores y cohesión seleccionados.',
                ]);
        }

        $interaction->update([
            'cohesion_id' => $data['cohesion_id'],
        ]);

        return redirect()
            ->route('admin.interactions.index')
            ->with('info', 'Interacción actualizada correctamente.');
    }

    public function destroy(Interaction $interaction)
    {
        $interaction->delete();

        return redirect()
            ->route('admin.interactions.index')
            ->with('info', 'Interacción eliminada correctamente.');
    }
}
