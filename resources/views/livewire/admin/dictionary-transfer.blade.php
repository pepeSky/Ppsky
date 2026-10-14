<div class="application-models mb-3">
    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="open">Importar términos</button>
    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="export" wire:loading.attr="disabled" wire:target="export">Exportar términos</button>
    @if ($message)<p class="text-muted mt-2 mb-0" role="status">{{ $message }}</p>@endif
    @if ($conflicts)<p class="text-warning mb-0">Omitidos: {{ implode(', ', $conflicts) }}</p>@endif
    <x-dialog-modal id="dictionary-import-popup" maxWidth="lg" wire:model="importing">
        <x-slot name="title">Importar términos</x-slot>
        <x-slot name="content">
            <form wire:submit="importSelected">
                <label for="dictionary-source">Desde otra aplicación del sistema activo</label>
                <select id="dictionary-source" class="form-control mb-2" wire:model.live="sourceId"><option value="">Seleccione una aplicación</option>@foreach ($sources as $source)<option value="{{ $source->id }}">{{ $source->name }}</option>@endforeach</select>
                <div style="max-height: 12rem; overflow-y: auto;">
                    @foreach ($sourceTerms as $term)
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="import-term-{{ $term->id }}" value="{{ $term->id }}" wire:model="selectedIds"><label class="form-check-label" for="import-term-{{ $term->id }}">{{ $term->name }}</label></div>
                    @endforeach
                </div>
                @if ($sourceId && $sourceTerms->isEmpty())<p class="text-muted">Sin términos disponibles.</p>@endif
                <button class="btn btn-primary btn-sm mt-2" wire:loading.attr="disabled" wire:target="importSelected">Importar selección</button>
            </form>
            <hr>
            <form wire:submit="importFile">
                <label for="dictionary-file">Desde archivo JSON exportado</label>
                <input id="dictionary-file" type="file" wire:model="file" accept=".json,application/json" class="form-control-file">
                <small class="text-muted">Máximo 2 MB y 1.000 términos. Los conflictos se omiten sin reemplazar sus datos.</small>
                <button class="btn btn-primary btn-sm d-block mt-2" wire:loading.attr="disabled" wire:target="file,importFile">Importar archivo</button>
            </form>
            @if ($errors->any())<ul class="text-danger mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
        </x-slot>
        <x-slot name="footer"><button type="button" class="btn btn-secondary" wire:click="$set('importing', false)">Cerrar</button></x-slot>
    </x-dialog-modal>
</div>
