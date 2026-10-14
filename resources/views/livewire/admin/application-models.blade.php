<div class="application-models {{ $compact ? 'mr-2' : 'card' }}">
    <div class="{{ $compact ? '' : 'card-body' }}">
        <button type="button" class="btn btn-outline-primary {{ $compact ? 'btn-sm' : 'float-right' }}" wire:click="open">{{ $compact ? 'Agregar término' : 'Agregar modelo' }}</button>
        @if (!$compact)<h2 class="h5">Modelos</h2>
        @include('admin.applications.model-summary')@endif
        @if ($message)<p class="text-success mb-0 mt-2" role="status">{{ $message }}</p>@endif
    </div>
    <x-dialog-modal id="application-model-popup" maxWidth="lg" wire:model="addingModel">
        <x-slot name="title">Agregar modelo</x-slot>
        <x-slot name="content">
            <form id="application-model-form" wire:submit="save" autocomplete="off">
                <div class="form-group"><label for="model-name">Nombre del modelo</label><input id="model-name" name="communication_model_term" autocomplete="off" wire:model="name" class="form-control" maxlength="255" @disabled($existingModelId !== null) @if($existingModelId === null) required @endif>@error('name')<p class="text-danger">{{ $message }}</p>@enderror</div>
                <div class="form-group"><label for="model-description">Descripción / referencia comunicacional</label><textarea id="model-description" name="communication_model_reference" autocomplete="off" wire:model="description" class="form-control" rows="4" maxlength="10000" @disabled($existingModelId !== null) @if($existingModelId === null) required @endif></textarea>@error('description')<p class="text-danger">{{ $message }}</p>@enderror</div>
                @if ($availableModels->isNotEmpty())
                    <details><summary>Asociar modelo existente</summary>
                        <label for="existing-model" class="sr-only">Modelo existente</label>
                        <select id="existing-model" autocomplete="off" wire:model.live="existingModelId" class="form-control mt-2"><option value="">Crear un modelo nuevo</option>@foreach ($availableModels as $model)<option value="{{ $model->id }}">{{ $model->name }}</option>@endforeach</select>
                        @error('existingModelId')<p class="text-danger">{{ $message }}</p>@enderror
                    </details>
                @endif
            </form>
        </x-slot>
        <x-slot name="footer">
            <button type="button" class="btn btn-secondary" wire:click="$set('addingModel', false)">Cancelar</button>
            <button type="submit" form="application-model-form" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">Agregar</button>
        </x-slot>
    </x-dialog-modal>
</div>
