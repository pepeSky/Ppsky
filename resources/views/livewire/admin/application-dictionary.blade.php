    <div class="application-models card"><div class="card-body">
        <form wire:submit="applySearch" class="mb-3">
            <label for="dictionary-search" class="sr-only">Buscar términos</label>
            <div class="input-group"><input id="dictionary-search" wire:model.live.debounce.250ms="search" class="form-control" placeholder="Buscar término o significado" maxlength="255"><div class="input-group-append"><button class="btn btn-primary">Buscar</button></div></div>
        </form>
        @if ($message)<p class="text-success" role="status">{{ $message }}</p>@endif
        <nav class="d-flex flex-wrap mb-3" aria-label="Índice alfabético">
            <button type="button" class="btn btn-sm {{ $letter === '' ? 'btn-primary' : 'btn-light' }} mr-1 mb-1" wire:click="selectLetter('')">Todos</button>
            @foreach ($this->alphabet() as $initial)<button type="button" class="btn btn-sm {{ $letter === $initial ? 'btn-primary' : 'btn-light' }} mr-1 mb-1" wire:click="selectLetter('{{ $initial }}')">{{ $initial }}</button>@endforeach
        </nav>
        <div class="row">
            <aside class="col-md-3 border-right">
                <p class="text-muted">{{ $terms->total() }} términos</p>
                <nav class="list-group dictionary-list" aria-label="Términos del diccionario">
                    @forelse ($terms as $term)
                        <a class="list-group-item list-group-item-action {{ $selected?->id === $term->id ? 'active' : '' }}" wire:key="dictionary-term-{{ $term->id }}" wire:click.prevent="selectTerm({{ $term->id }})" href="{{ route('admin.applications.dictionary', ['application' => $application, 'q' => $search, 'term' => $term->id]) }}" @if($selected?->id === $term->id) aria-current="true" @endif>{{ $term->name }}</a>
                    @empty <p class="text-muted">No hay términos coincidentes.</p> @endforelse
                </nav>
                <div class="mt-3">{{ $terms->links() }}</div>
            </aside>
            <article class="col-md-9 dictionary-entry pl-md-4">
                @if ($selected)
                    <div class="float-right btn-group" role="group" aria-label="Acciones del término">
                        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="viewTerm({{ $selected->id }})" title="Ver" aria-label="Ver"><i class="fas fa-eye" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="editTerm({{ $selected->id }})" title="Editar" aria-label="Editar"><i class="fas fa-pen" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="confirmUnlink({{ $selected->id }})" title="Desvincular de esta aplicación" aria-label="Desvincular"><i class="fas fa-unlink" aria-hidden="true"></i></button>
                    </div>
                    <h2>{{ $selected->name }}</h2>
                    <div class="mb-2">
                        <button type="button" class="btn btn-link btn-sm pl-0" wire:click="openDefinition({{ $selected->id }})">Agregar definición / lenguaje</button>
                        <button type="button" class="btn btn-link btn-sm" wire:click="openRelation({{ $selected->id }})">Relacionar término</button>
                    </div>
                    <h3 class="h6 mt-4">Referencia comunicacional del usuario</h3>
                    <p class="dictionary-text">{{ $selected->description ?: 'Sin descripción registrada.' }}</p>
                    @forelse ($selected->definitions->groupBy(fn ($definition) => $definition->language_id ?? 'none') as $definitions)
                        <section class="border-top mt-4 pt-3">
                            <h3 class="h5">{{ $definitions->first()->language?->name ?? 'Sin lenguaje especificado' }}</h3>
                            @foreach ($definitions as $definition)<p class="dictionary-text">{{ $definition->definition }}</p>@endforeach
                        </section>
                    @empty <p class="text-muted">Sin definiciones por lenguaje registradas.</p> @endforelse
                    @if ($relations->isNotEmpty())
                        <section class="border-top mt-4 pt-3"><h3 class="h5">Relaciones del modelo</h3>
                            @foreach ($relations as $relation)
                                @php($outgoing = $relation->source_model_id === $selected->id)
                                @php($other = $outgoing ? $relation->target : $relation->source)
                                <div class="mb-2" wire:key="relation-{{ $relation->id }}">
                                    {{ config('model_relations.'.$relation->type.'.'.($outgoing ? 'label' : 'inverse'), $relation->type) }}
                                    <button type="button" class="btn btn-link btn-sm" wire:click="selectTerm({{ $other->id }})">{{ $other->name }}</button>
                                    <button type="button" class="btn btn-link btn-sm text-muted" title="Desactivar relación conservando su registro" aria-label="Desactivar relación" wire:click="deactivateRelation({{ $relation->id }})"><i class="fas fa-unlink" aria-hidden="true"></i></button>
                                    @if ($relation->description)<p class="dictionary-text text-muted mb-0">{{ $relation->description }}</p>@endif
                                </div>
                            @endforeach
                        </section>
                    @endif
                @else <p class="text-muted">Agregue modelos desde la edición de la aplicación para construir su diccionario.</p> @endif
            </article>
        </div>
    </div>
    <x-dialog-modal id="dictionary-view-term" maxWidth="lg" wire:model="viewing">
        <x-slot name="title">Ficha del modelo</x-slot>
        <x-slot name="content">
            @if ($selected)
                <h3 class="h5">{{ $selected->name }}</h3>
                <p class="dictionary-text">{{ $selected->description }}</p>
                <dl><dt>Registrado</dt><dd>{{ $selected->created_at }}</dd><dt>Actualizado</dt><dd>{{ $selected->updated_at }}</dd></dl>
                @foreach ($selected->definitions as $definition)<h4 class="h6">{{ $definition->language?->name }}</h4><p class="dictionary-text">{{ $definition->definition }}</p>@endforeach
            @endif
        </x-slot>
        <x-slot name="footer"><button class="btn btn-secondary" wire:click="$set('viewing', false)">Cerrar</button></x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="dictionary-edit-term" maxWidth="lg" wire:model="editing">
        <x-slot name="title">Editar modelo</x-slot>
        <x-slot name="content">
            <form id="dictionary-edit-form" wire:submit="saveTerm" autocomplete="off">
                <label for="edit-term-name">Nombre</label><input id="edit-term-name" name="dictionary_term" wire:model="editName" autocomplete="off" class="form-control mb-3" maxlength="255" required>
                <label for="edit-term-description">Descripción / referencia comunicacional</label><textarea id="edit-term-description" name="dictionary_reference" wire:model="editDescription" autocomplete="off" class="form-control" rows="4" maxlength="10000" required></textarea>
                @if ($errors->any())<ul class="text-danger mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
            </form>
        </x-slot>
        <x-slot name="footer"><button class="btn btn-secondary" wire:click="$set('editing', false)">Cancelar</button><button type="submit" form="dictionary-edit-form" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveTerm">Guardar</button></x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="dictionary-unlink-term" maxWidth="lg" wire:model="unlinking">
        <x-slot name="title">Desvincular término</x-slot>
        <x-slot name="content">¿Retirar «{{ $selected?->name }}» de esta aplicación? El modelo, sus definiciones y la asociación se conservarán. Puede volver a asociarlo desde Agregar modelo.</x-slot>
        <x-slot name="footer"><button class="btn btn-secondary" wire:click="$set('unlinking', false)">Cancelar</button><button class="btn btn-primary" wire:click="unlinkTerm" wire:loading.attr="disabled">Desvincular</button></x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="dictionary-definition-modal" maxWidth="lg" wire:model="addingDefinition">
        <x-slot name="title">Agregar definición y lenguaje</x-slot>
        <x-slot name="content">
            <form id="definition-form" wire:submit="saveDefinition" autocomplete="off">
                <div class="form-check mb-3"><input type="checkbox" id="new-language" class="form-check-input" wire:model.live="newLanguage"><label for="new-language" class="form-check-label">Registrar un lenguaje o notación nuevo</label></div>
                @if ($newLanguage)
                    <label for="language-name">Nombre del lenguaje / notación</label><input id="language-name" wire:model="languageName" autocomplete="off" class="form-control mb-2" maxlength="255" required>
                    <label for="language-code">Código (opcional)</label><input id="language-code" wire:model="languageCode" autocomplete="off" class="form-control mb-3" maxlength="255">
                @else
                    <label for="language-id">Lenguaje / notación</label><select id="language-id" wire:model="languageId" class="form-control mb-3" required><option value="">Seleccione</option>@foreach ($languages as $language)<option value="{{ $language->id }}">{{ $language->name }}</option>@endforeach</select>
                @endif
                <label for="definition-text">Definición / representación del modelo</label><textarea id="definition-text" wire:model="definitionText" class="form-control" rows="5" maxlength="10000" autocomplete="off" required></textarea>
                @if ($errors->any())<ul class="text-danger mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
            </form>
        </x-slot>
        <x-slot name="footer"><button type="button" class="btn btn-secondary" wire:click="$set('addingDefinition', false)">Cancelar</button><button type="submit" form="definition-form" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveDefinition">Agregar</button></x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="dictionary-relation-modal" maxWidth="lg" wire:model="addingRelation">
        <x-slot name="title">Relacionar modelos de la aplicación</x-slot>
        <x-slot name="content">
            <form id="relation-form" wire:submit="saveRelation">
                <label for="relation-target">Término de destino</label><select id="relation-target" wire:model="relationTargetId" class="form-control mb-3" required><option value="">Seleccione</option>@foreach ($relationTerms as $other)<option value="{{ $other->id }}">{{ $other->name }}</option>@endforeach</select>
                <label for="relation-type">Relación</label><select id="relation-type" wire:model="relationType" class="form-control mb-3">@foreach (config('model_relations') as $key => $type)<option value="{{ $key }}">{{ $type['label'] }}</option>@endforeach<option value="specific">Más específico que</option><option value="part_of">Forma parte de</option></select>
                <label for="relation-description">Explicación (opcional)</label><textarea id="relation-description" wire:model="relationDescription" class="form-control" rows="3" maxlength="10000"></textarea>
                <small class="text-muted">Esta relación conserva ambos modelos. No establece una unificación jurídica ni genera código.</small>
                @if ($errors->any())<ul class="text-danger mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
            </form>
        </x-slot>
        <x-slot name="footer"><button type="button" class="btn btn-secondary" wire:click="$set('addingRelation', false)">Cancelar</button><button type="submit" form="relation-form" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveRelation">Guardar relación</button></x-slot>
    </x-dialog-modal>
</div>
