<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\ApplicationModelRelation;
use App\Models\Language;
use App\Services\MeWo\ApplicationAccess;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationDictionary extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Locked]
    public int $applicationId;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: null)]
    public ?int $term = null;

    public bool $viewing = false;
    public bool $editing = false;
    public bool $unlinking = false;
    #[Locked]
    public ?int $editingId = null;
    public string $editName = '';
    public string $editDescription = '';
    public string $message = '';

    #[Url(except: '')]
    public string $letter = '';
    public bool $addingDefinition = false;
    public bool $addingRelation = false;
    #[Locked]
    public ?int $definitionModelId = null;
    #[Locked]
    public ?int $relationModelId = null;
    public ?int $languageId = null;
    public bool $newLanguage = false;
    public string $languageName = '';
    public string $languageCode = '';
    public string $definitionText = '';
    public ?int $relationTargetId = null;
    public string $relationType = 'related';
    public string $relationDescription = '';

    public function alphabet(): array
    {
        return array_merge(range('A', 'N'), ['Ñ'], range('O', 'Z'), ['#']);
    }

    private function initial(string $name): string
    {
        $char = mb_strtoupper(mb_substr(trim($name), 0, 1));
        $char = strtr($char, ['Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E','Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I','Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O','Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U']);
        return in_array($char, $this->alphabet(), true) ? $char : '#';
    }

    public function selectLetter(string $letter): void
    {
        abort_unless($letter === '' || in_array($letter, $this->alphabet(), true), 422);
        $this->application();
        $this->letter = $letter;
        $this->term = null;
        $this->resetPage();
    }

    public function openDefinition(int $id): void
    {
        $this->selectTerm($id);
        $this->selectedModel();
        $this->reset(['languageId', 'newLanguage', 'languageName', 'languageCode', 'definitionText']);
        $this->resetValidation();
        $this->definitionModelId = $id;
        $this->addingDefinition = true;
    }

    public function saveDefinition(): void
    {
        $application = $this->application();
        $model = $application->models()->where('models.user_id', auth()->id())->find($this->definitionModelId);
        abort_unless($model, 404);
        $rules = ['definitionText' => ['required', 'string', 'max:10000', 'regex:/\S/u']];
        if ($this->newLanguage) {
            $rules['languageName'] = ['required', 'string', 'max:255', 'regex:/\S/u', Rule::unique('languages', 'name')];
            $rules['languageCode'] = ['nullable', 'string', 'max:255'];
        } else { $rules['languageId'] = ['required', 'integer', Rule::exists('languages', 'id')]; }
        $this->validate($rules);
        DB::transaction(function () use ($model, $application) {
            $model = $model->newQuery()->whereKey($model->id)->lockForUpdate()->firstOrFail();
            if ($model->applications()->where('applications.id', '!=', $application->id)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages(['definitionText' => 'El modelo está compartido. Primero debe revisarse su independencia o el acuerdo correspondiente.']);
            }
            $language = $this->newLanguage
                ? Language::create(['name' => $this->languageName, 'code' => $this->languageCode ?: null])
                : Language::findOrFail($this->languageId);
            $model->definitions()->firstOrCreate(['language_id' => $language->id, 'definition' => $this->definitionText]);
        });
        $this->addingDefinition = false;
        $this->message = 'Definición agregada. El lenguaje queda referenciado por el diccionario.';
        $this->dispatch('dictionary-updated');
    }

    public function openRelation(int $id): void
    {
        $this->selectTerm($id);
        $this->selectedModel();
        $this->reset(['relationTargetId', 'relationType', 'relationDescription']);
        $this->resetValidation();
        $this->relationModelId = $id;
        $this->addingRelation = true;
    }

    public function saveRelation(): void
    {
        $application = $this->application();
        $this->validate([
            'relationTargetId' => ['required', 'integer'],
            'relationType' => ['required', Rule::in(array_merge(array_keys(config('model_relations')), ['specific', 'part_of']))],
            'relationDescription' => ['nullable', 'string', 'max:10000'],
        ]);
        $ids = [$this->relationModelId, $this->relationTargetId];
        foreach ($ids as $id) {
            abort_unless($application->models()->where('models.user_id', auth()->id())->whereKey($id)->exists(), 404);
        }
        $type = $this->relationType;
        if (in_array($type, ['specific', 'part_of'], true)) {
            $ids = array_reverse($ids);
            $type = $type === 'specific' ? 'broader' : 'contains';
        }
        if (config('model_relations.'.$type.'.symmetric')) { sort($ids); }
        ApplicationModelRelation::updateOrCreate([
            'application_id' => $application->id, 'source_model_id' => $ids[0], 'target_model_id' => $ids[1], 'type' => $type,
        ], ['description' => $this->relationDescription ?: null, 'is_active' => true]);
        $this->addingRelation = false;
        $this->message = 'Relación registrada dentro de esta aplicación. Los modelos conservan su identidad.';
    }

    public function deactivateRelation(int $id): void
    {
        $application = $this->application();
        $relation = ApplicationModelRelation::where('application_id', $application->id)->find($id);
        abort_unless($relation, 404);
        $relation->update(['is_active' => false]);
    }

    public function mount(int $applicationId, string $initialSearch = '', ?int $initialTerm = null): void
    {
        $this->applicationId = $applicationId;
        $this->search = $initialSearch;
        $this->term = $initialTerm;
        $this->application();
    }

    private function application(): Application
    {
        $application = Application::findOrFail($this->applicationId);
        app(ApplicationAccess::class)->check(request(), $application);
        return $application;
    }

    public function selectTerm(int $id): void
    {
        $selected = $this->application()->models()->find($id);
        abort_unless($selected, 404);
        $this->term = $selected->id;
    }

    private function selectedModel()
    {
        $model = $this->application()->models()->where('models.user_id', auth()->id())->find($this->term);
        abort_unless($model, 404);
        return $model;
    }

    public function viewTerm(int $id): void
    {
        $this->selectTerm($id);
        $this->selectedModel();
        $this->viewing = true;
    }

    public function editTerm(int $id): void
    {
        $this->selectTerm($id);
        $model = $this->selectedModel();
        $this->resetValidation();
        $this->editingId = $model->id;
        $this->editName = $model->name;
        $this->editDescription = $model->description ?? '';
        $this->editing = true;
    }

    public function saveTerm(): void
    {
        $application = $this->application();
        $model = $application->models()->where('models.user_id', auth()->id())->find($this->editingId);
        abort_unless($model, 404);
        $data = $this->validate([
            'editName' => ['required', 'string', 'max:255', 'regex:/\S/u', Rule::unique('models', 'name')->where('user_id', auth()->id())->ignore($model->id)],
            'editDescription' => ['required', 'string', 'max:10000', 'regex:/\S/u'],
        ]);
        DB::transaction(function () use ($model, $application, $data) {
            $model = $model->newQuery()->whereKey($model->id)->lockForUpdate()->firstOrFail();
            if ($model->applications()->where('applications.id', '!=', $application->id)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages(['editDescription' => 'Este modelo está compartido con otras aplicaciones. La edición requiere revisar su independencia o el acuerdo correspondiente; no se modificarán las otras apps.']);
            }
            $model->update(['name' => $data['editName'], 'description' => $data['editDescription']]);
        });
        $this->editing = false;
        $this->message = 'Modelo actualizado.';
    }

    public function confirmUnlink(int $id): void
    {
        $this->selectTerm($id);
        $this->selectedModel();
        $this->unlinking = true;
    }

    public function unlinkTerm(): void
    {
        $model = $this->selectedModel();
        $this->application()->modelAssociations()->updateExistingPivot($model->id, ['is_active' => false]);
        $this->term = null;
        $this->unlinking = false;
        $this->viewing = false;
        $this->editing = false;
        $this->message = 'Término desvinculado de esta aplicación. El modelo y su asociación se conservan.';
        $this->resetPage();
        $this->dispatch('dictionary-updated');
    }

    public function updatedSearch(): void
    {
        $this->applySearch();
    }

    public function applySearch(): void
    {
        $this->application();
        $this->validate(['search' => ['string', 'max:255']]);
        $this->term = null;
        $this->resetPage();
    }

    #[On('dictionary-updated')]
    public function refreshTerms(): void
    {
        $this->application();
    }

    public function updatedPaginators(): void
    {
        $this->term = null;
    }

    public function render()
    {
        $application = $this->application();
        $search = trim(mb_substr($this->search, 0, 255));
        $query = $application->models()->with('definitions.language')->orderBy('name');
        if ($search !== '') {
            $query->where(fn ($query) => $query->where('models.name', 'like', '%'.$search.'%')
                ->orWhere('models.description', 'like', '%'.$search.'%')
                ->orWhereHas('definitions', fn ($definitions) => $definitions->where('definition', 'like', '%'.$search.'%')));
        }
        if ($this->letter !== '') {
            $ids = $application->models()->get(['models.id', 'models.name'])->filter(fn ($model) => $this->initial($model->name) === $this->letter)->pluck('id');
            $query->whereIn('models.id', $ids);
        }
        $terms = $query->paginate(30);
        $selected = $this->term !== null
            ? $application->models()->with('definitions.language')->find($this->term)
            : $terms->first();
        abort_if($this->term !== null && ! $selected, 404);
        $languages = Language::orderBy('name')->get();
        $relationTerms = $application->models()->where('models.user_id', auth()->id())->orderBy('name')->get();
        $activeIds = $relationTerms->pluck('id');
        $relations = $selected ? ApplicationModelRelation::where('application_id', $application->id)->where('is_active', true)
            ->whereIn('source_model_id', $activeIds)->whereIn('target_model_id', $activeIds)
            ->where(fn ($query) => $query->where('source_model_id', $selected->id)->orWhere('target_model_id', $selected->id))
            ->with(['source', 'target'])->get() : collect();
        return view('livewire.admin.application-dictionary', compact('application', 'terms', 'selected', 'search', 'languages', 'relationTerms', 'relations'));
    }
}
