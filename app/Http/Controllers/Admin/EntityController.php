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

    public function create(Request $request)
    {
        $this->requireManagement($request);
        $natures = Nature::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        return view('admin.entities.create', compact('natures', 'types'));
    }

    public function store(Request $request)
    {
        $this->requireManagement($request);
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

    public function edit(Request $request, Entity $entity)
    {
        $this->requireManagement($request,$entity);
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
        $this->requireManagement($request,$entity);
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

    public function destroy(Request $request, Entity $entity)
    {
        $this->requireManagement($request,$entity);
        if($entity->identities()->exists()) { return back()->withErrors(['entity'=>'Esta entidad tiene identidades asociadas y debe conservarse.']); }
        $entity->delete();

        return redirect()
            ->route('admin.entities.index')
            ->with('info', 'Entidad eliminada correctamente.');
    }
    private function requireManagement(Request $request, ?Entity $entity=null): void
    {
        $access=app(\App\Services\MeWo\ActorAccess::class);
        $access->require($request,$access->system($request),'identities.manage');
        if($entity) { abort_if($entity->identities()->where('user_id','!=',$request->user()->id)->exists(),403); }
    }
}
