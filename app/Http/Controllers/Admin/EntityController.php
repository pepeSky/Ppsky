<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Nature;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EntityController extends Controller
{
    public function index()
    {
        $entities = Entity::with(['nature', 'types'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.entities.index', compact('entities'));
    }

    public function create()
    {
        $natures = Nature::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        return view('admin.entities.create', compact('natures', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
            'nature_id' => ['required', Rule::exists('natures', 'id')],
            'types' => ['nullable', 'array'],
            'types.*' => [Rule::exists('types', 'id')],
        ]);

        $entity = Entity::create([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'nature_id' => $data['nature_id'],
        ]);

        $entity->types()->sync($data['types'] ?? []);

        return redirect()
            ->route('admin.entities.index')
            ->with('info', 'Entidad registrada correctamente.');
    }

    public function show(Entity $entity)
    {
        $entity->load(['nature', 'types']);

        return view('admin.entities.show', compact('entity'));
    }

    public function edit(Entity $entity)
    {
        $entity->load('types');

        $natures = Nature::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        return view(
            'admin.entities.edit',
            compact('entity', 'natures', 'types')
        );
    }

    public function update(Request $request, Entity $entity)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
            'nature_id' => ['required', Rule::exists('natures', 'id')],
            'types' => ['nullable', 'array'],
            'types.*' => [Rule::exists('types', 'id')],
        ]);

        $entity->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'nature_id' => $data['nature_id'],
        ]);

        $entity->types()->sync($data['types'] ?? []);

        return redirect()
            ->route('admin.entities.index')
            ->with('info', 'Entidad actualizada correctamente.');
    }

    public function destroy(Entity $entity)
    {
        $entity->delete();

        return redirect()
            ->route('admin.entities.index')
            ->with('info', 'Entidad eliminada correctamente.');
    }
}
