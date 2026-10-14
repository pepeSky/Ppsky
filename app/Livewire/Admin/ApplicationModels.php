<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Services\MeWo\ApplicationAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ApplicationModels extends Component
{
    #[Locked]
    public int $applicationId;
    public bool $addingModel = false;
    public string $name = '';
    public string $description = '';
    public ?int $existingModelId = null;
    public string $message = '';
    #[Locked]
    public bool $compact = false;

    public function mount(int $applicationId, bool $compact = false): void
    {
        $this->applicationId = $applicationId;
        $this->compact = $compact;
        $this->application();
    }

    private function application(): Application
    {
        $application = Application::findOrFail($this->applicationId);
        app(ApplicationAccess::class)->check(request(), $application);
        return $application;
    }

    public function open(): void
    {
        $this->application();
        $this->reset(['name', 'description', 'existingModelId', 'message']);
        $this->resetValidation();
        $this->addingModel = true;
    }

    public function save(): void
    {
        $application = $this->application();
        if ($this->existingModelId !== null) {
            $this->validate(['existingModelId' => ['required', 'integer', Rule::exists('models', 'id')->where('user_id', auth()->id())]]);
            $application->associateModels([$this->existingModelId]);
        } else {
            $data = $this->validate([
                'name' => ['required', 'string', 'max:255', 'regex:/\S/u', Rule::unique('models')->where('user_id', auth()->id())],
                'description' => ['required', 'string', 'max:10000', 'regex:/\S/u'],
            ], ['name.unique' => 'Este término ya existe en su diccionario. Utilice Asociar modelo existente.']);
            DB::transaction(function () use ($application, $data) {
                $model = auth()->user()->models()->create($data);
                $application->associateModels([$model->id]);
            });
        }
        $this->addingModel = false;
        $this->message = 'Modelo agregado a la aplicación.';
        $this->dispatch('dictionary-updated');
    }

    public function render()
    {
        $application = $this->application()->load('models.definitions.language');
        $availableModels = auth()->user()->models()->whereDoesntHave('applications', fn ($query) => $query->where('applications.id', $application->id))->orderBy('name')->get();
        return view('livewire.admin.application-models', compact('application', 'availableModels'));
    }
}
