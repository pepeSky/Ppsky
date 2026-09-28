<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Model as InformationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = $request->user()->applications()
            ->with('models')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    public function create(Request $request)
    {
        $models = $this->availableModels($request);

        return view('admin.applications.create', compact('models'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        DB::transaction(function () use ($request, $validated) {
            $application = $request->user()->applications()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'location' => $validated['location'] ?? null,
            ]);
            $application->models()->sync($validated['model_ids'] ?? []);
        });

        return redirect()->route('admin.applications.index')
            ->with('info', 'Aplicación creada correctamente.');
    }

    public function show(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        $application->load('models.definitions.language');

        return view('admin.applications.show', compact('application'));
    }

    public function edit(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        $application->load('models');
        $models = $this->availableModels($request);

        return view('admin.applications.edit', compact('application', 'models'));
    }

    public function update(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        $validated = $this->validatedData($request, $application);

        DB::transaction(function () use ($application, $validated) {
            $application->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'location' => $validated['location'] ?? null,
            ]);
            $application->models()->sync($validated['model_ids'] ?? []);
        });

        return redirect()->route('admin.applications.show', $application)
            ->with('info', 'Aplicación modificada correctamente.');
    }

    public function destroy(Request $request, Application $application)
    {
        $this->ensureOwner($request, $application);
        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('info', 'Aplicación eliminada correctamente.');
    }

    private function ensureOwner(Request $request, Application $application): void
    {
        abort_unless($application->user_id === $request->user()->id, 404);
    }

    private function availableModels(Request $request)
    {
        return $request->user()->models()->orderBy('name')->get();
    }

    private function validatedData(Request $request, ?Application $application = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('applications', 'name')
                    ->where('user_id', $request->user()->id)
                    ->ignore($application?->id),
            ],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'model_ids' => ['sometimes', 'array'],
            'model_ids.*' => [
                'integer', 'distinct',
                Rule::exists('models', 'id')->where('user_id', $request->user()->id),
            ],
        ]);
    }
}
