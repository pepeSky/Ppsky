<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Identity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IdentityController extends Controller
{
    public function index()
    {
        $identities = Identity::with(['entity.nature', 'actor'])
            ->where('user_id', auth()->id())
            ->orderBy('id')
            ->paginate(15);

        return view('admin.identities.index', compact('identities'));
    }

    public function create()
    {
        $entities = Entity::with('nature')
            ->whereDoesntHave('identities', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('name')
            ->get();

        return view('admin.identities.create', compact('entities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entity_id' => [
                'required',
                Rule::exists('entities', 'id'),
                Rule::unique('identities', 'entity_id')
                    ->where(fn ($query) =>
                        $query->where('user_id', auth()->id())
                    ),
            ],
            'identification' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        Identity::create([
            'user_id' => auth()->id(),
            'entity_id' => $data['entity_id'],
            'identification' => $data['identification'] ?? null,
        ]);

        return redirect()
            ->route('admin.identities.index')
            ->with('info', 'Identidad registrada correctamente.');
    }

    public function show(Identity $identity)
    {
        $this->authorizeIdentity($identity);

        $identity->load(['entity.nature', 'actor']);
        
        return view('admin.identities.show', compact('identity'));
    }

    public function edit(Identity $identity)
    {
        $this->authorizeIdentity($identity);
        $identity->load('entity');
        return view('admin.identities.edit', compact('identity'));
    }

    public function update(Request $request, Identity $identity)
    {
        $this->authorizeIdentity($identity);

        $data = $request->validate([
            'identification' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $identity->update([
            'identification' => $data['identification'] ?? null,
        ]);

        return redirect()
            ->route('admin.identities.index')
            ->with('info', 'Identidad actualizada correctamente.');
    }

    public function destroy(Identity $identity)
    {
        $this->authorizeIdentity($identity);

        $identity->delete();

        return redirect()
            ->route('admin.identities.index')
            ->with('info', 'Identidad eliminada correctamente.');
    }

    private function authorizeIdentity(Identity $identity): void
    {
        abort_unless(
            $identity->user_id === auth()->id(),
            403
        );
    }
}
