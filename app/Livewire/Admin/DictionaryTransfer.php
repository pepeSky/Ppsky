<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Services\MeWo\ApplicationAccess;
use App\Services\MeWo\DictionaryTransfer as Transfer;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class DictionaryTransfer extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $applicationId;
    public bool $importing = false;
    public ?int $sourceId = null;
    public array $selectedIds = [];
    public $file;
    public string $message = '';
    public array $conflicts = [];

    public function mount(int $applicationId): void
    {
        $this->applicationId = $applicationId;
        $this->application();
    }

    private function application(): Application
    {
        $application = Application::findOrFail($this->applicationId);
        app(ApplicationAccess::class)->check(request(), $application);
        return $application;
    }

    private function sources()
    {
        $system = app(ApplicationAccess::class)->system(request());
        return auth()->user()->applications()->where('id', '!=', $this->applicationId)
            ->where('system_id', $system->id)->orderBy('name');
    }

    public function open(): void
    {
        $this->application();
        $this->reset(['sourceId', 'selectedIds', 'file', 'message', 'conflicts']);
        $this->resetValidation();
        $this->importing = true;
    }

    public function updatedSourceId(): void
    {
        $this->selectedIds = [];
    }

    public function importSelected(): void
    {
        $application = $this->application();
        $this->validate(['sourceId' => ['required', 'integer'], 'selectedIds' => ['required', 'array', 'min:1', 'max:1000'], 'selectedIds.*' => ['integer', 'distinct']]);
        $source = $this->sources()->find($this->sourceId);
        abort_unless($source, 404);
        $ids = $source->models()->where('models.user_id', auth()->id())->whereIn('models.id', $this->selectedIds)->pluck('models.id');
        if ($ids->count() !== count($this->selectedIds)) {
            throw ValidationException::withMessages(['selectedIds' => 'La selección contiene términos que no pertenecen al diccionario de origen.']);
        }
        $changes = $application->associateModels($ids->all());
        $this->message = count($changes['attached']).' términos agregados; '.(count($ids) - count($changes['attached'])).' ya estaban asociados.';
        $this->complete();
    }

    public function importFile(): void
    {
        $application = $this->application();
        $this->validate(['file' => ['required', 'file', 'max:2048']]);
        try { $payload = json_decode(file_get_contents($this->file->getRealPath()), true, 512, JSON_THROW_ON_ERROR); }
        catch (\JsonException $e) { throw ValidationException::withMessages(['file' => 'El archivo no contiene JSON válido.']); }
        if (! is_array($payload)) { throw ValidationException::withMessages(['file' => 'El archivo no contiene un diccionario válido.']); }
        $result = app(Transfer::class)->import(auth()->user(), $application, $payload);
        $this->message = $result['added'].' términos agregados; '.$result['existing'].' ya asociados; '.count($result['conflicts']).' omitidos por conflicto de descripción o lenguaje.';
        $this->conflicts = $result['conflicts'];
        $this->complete();
    }

    private function complete(): void
    {
        $this->importing = false;
        $this->reset('file');
        $this->dispatch('dictionary-updated');
    }

    public function export()
    {
        $payload = app(Transfer::class)->export($this->application());
        return response()->streamDownload(fn () => print(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)), 'diccionario-app-'.$this->applicationId.'.json', ['Content-Type' => 'application/json']);
    }

    public function render()
    {
        $this->application();
        $sources = $this->sources()->get();
        $source = $this->sourceId ? $sources->firstWhere('id', $this->sourceId) : null;
        $sourceTerms = $source ? $source->models()->where('models.user_id', auth()->id())->orderBy('name')->get() : collect();
        return view('livewire.admin.dictionary-transfer', compact('sources', 'sourceTerms'));
    }
}
