<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Identity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::with('identity.entity')
            ->orderBy('id')
            ->paginate(15);

        return view('admin.actors.index', compact('actors'));
    }

    public function create()
    {
        $identities = Identity::with('entity')
            ->whereDoesntHave('actor')
            ->orderBy('id')
            ->get();

        return view('admin.actors.create', compact('identities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'identity_id' => [
                'required',
                Rule::exists('identities', 'id'),
                Rule::unique('actors', 'identity_id'),
            ],
        ]);

        Actor::create([
            'identity_id' => $request->identity_id,
        ]);

        return redirect()
            ->route('admin.actors.index')
            ->with('info', 'Actor registrado correctamente.');
    }

    public function show(Actor $actor)
    {
        $actor->load('identity.entity');

        return view('admin.actors.show', compact('actor'));
    }

    public function edit(Actor $actor)
    {
        $identities = Identity::with('entity')
            ->where(function ($query) use ($actor) {
                $query->whereDoesntHave('actor')
                    ->orWhere('id', $actor->identity_id);
            })
            ->orderBy('id')
            ->get();

        return view('admin.actors.edit', compact('actor', 'identities'));
    }

    public function update(Request $request, Actor $actor)
    {
        $request->validate([
            'identity_id' => [
                'required',
                Rule::exists('identities', 'id'),
                Rule::unique('actors', 'identity_id')
                    ->ignore($actor->id),
            ],
        ]);

        $actor->update([
            'identity_id' => $request->identity_id,
        ]);

        return redirect()
            ->route('admin.actors.index')
            ->with('info', 'Actor actualizado correctamente.');
    }

    public function destroy(Actor $actor)
    {
        $actor->delete();

        return redirect()
            ->route('admin.actors.index')
            ->with('info', 'Actor eliminado correctamente.');
    }
}
