<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\MeWo\ApplicationAccess;
use App\Services\MeWo\ApplicationNaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $activeSystem = app(ApplicationAccess::class)->system($request);
        $applications = $request->user()->applications()
            ->with(['models.definitions.language', 'system.identity.entity'])
            ->where(fn ($query) => $query->whereNull('system_id')->orWhere('system_id', $activeSystem->id))
            ->orderBy('name')
            ->paginate(15);

        return view('admin.applications.index', compact('applications', 'activeSystem'));
    }

    public function create(Request $request)
    {
        $activeSystem = app(ApplicationAccess::class)->system($request);
        return view('admin.applications.create', compact('activeSystem'));
    }

    public function store(Request $request)
    {
        $activeSystem = app(ApplicationAccess::class)->system($request);
        $validated = $this->validatedData($request);

        $application = DB::transaction(function () use ($request, $validated, $activeSystem) {
            $application = $request->user()->applications()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'location' => $validated['location'] ?? null,
                'system_id' => $activeSystem->id,
                'compatible_platforms' => $validated['compatible_platforms'] ?? [],
                'execution_requirements' => $validated['execution_requirements'] ?? null,
            ]);
            return $application;
        });

        return redirect()->route('admin.applications.edit', $application)
            ->with('info', 'Aplicación creada correctamente.');
    }

    public function show(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        $application->load(['models.definitions.language', 'system.identity.entity']);

        return view('admin.applications.show', compact('application'));
    }

    public function edit(Request $request, Application $application)
    {
        $activeSystem = $this->ensureOwner($request, $application);
        return view('admin.applications.edit', compact('application', 'activeSystem'));
    }

    public function update(Request $request, Application $application)
    {
        $activeSystem = $this->ensureOwner($request, $application);
        $validated = $this->validatedData($request, $application);

        DB::transaction(function () use ($application, $validated, $activeSystem) {
            $application->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'location' => $validated['location'] ?? null,
                'system_id' => $application->system_id ?? $activeSystem->id,
                'compatible_platforms' => $validated['compatible_platforms'] ?? [],
                'execution_requirements' => $validated['execution_requirements'] ?? $application->execution_requirements,
            ]);

        });

        return redirect()->route('admin.applications.show', $application)
            ->with('info', 'Aplicación modificada correctamente.');
    }

    public function destroy(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        abort(403, 'La eliminación debe solicitarse a la administración de comunicación, con respaldos y resguardos legales.');
    }

    private function ensureOwner(Request $request, Application $application): \App\Models\System
    {
        return app(ApplicationAccess::class)->check($request, $application);
    }

    public function dictionary(Request $request, Application $application)
    {
        $activeSystem = $this->ensureOwner($request, $application);
        $data = $request->validate(['q' => ['nullable', 'string', 'max:255'], 'term' => ['nullable', 'integer']]);
        if (isset($data['term'])) {
            $application->models()->findOrFail($data['term']);
        }
        $initialSearch = trim($data['q'] ?? '');
        $initialTerm = isset($data['term']) ? (int) $data['term'] : null;
        return view('admin.applications.dictionary', compact('application', 'activeSystem', 'initialSearch', 'initialTerm'));
    }

    public function storeModel(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        $data = $request->validate([
            'model_name' => ['required', 'string', 'max:255', 'regex:/\S/u', Rule::unique('models', 'name')->where('user_id', $request->user()->id)],
            'model_description' => ['required', 'string', 'max:10000', 'regex:/\S/u'],
        ]);
        DB::transaction(function () use ($request, $application, $data) {
            $model = $request->user()->models()->create(['name' => $data['model_name'], 'description' => $data['model_description']]);
            $application->associateModels([$model->id]);
        });
        return redirect()->route('admin.applications.edit', $application)
            ->with('info', 'Modelo agregado al diccionario de la aplicación.');
    }

    private function validatedData(Request $request, ?Application $application = null): array
    {
        $input = $request->validate(['name' => ['required', 'string', 'max:255']]);
        $naming = app(ApplicationNaming::class);
        $system = app(ApplicationAccess::class)->system($request);
        $request->merge([
            'name' => $naming->name($input['name']),
            'description' => $naming->description($input['name'], $system->identity->display_name),
        ]);
        return $request->validate([
            'name' => [
                'required', 'string', 'max:255', 'regex:/\S/u',
                Rule::unique('applications', 'name')
                    ->where('user_id', $request->user()->id)
                    ->ignore($application?->id),
            ],
            'system_id' => ['prohibited'],
            'model_ids' => ['prohibited'],
            'compatible_platforms' => ['sometimes', 'array'],
            'compatible_platforms.*' => ['string', 'distinct', Rule::in(array_keys(config('application_platforms')))],
            'execution_requirements' => ['nullable', 'string', 'max:10000'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],

        ]);
    }
}
