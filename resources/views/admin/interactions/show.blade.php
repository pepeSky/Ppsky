<x-admin-layout>

    <x-slot name="content_header">
        <h1>Interacción</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <dl class="row">

                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">
                    {{ $interaction->id }}
                </dd>

                <dt class="col-sm-3">Primer actor</dt>
                <dd class="col-sm-9">
                    {{ $interaction->actorA->identity->entity->name }}
                </dd>

                <dt class="col-sm-3">Relación</dt>
                <dd class="col-sm-9">
                    ↔
                </dd>

                <dt class="col-sm-3">Segundo actor</dt>
                <dd class="col-sm-9">
                    {{ $interaction->actorB->identity->entity->name }}
                </dd>

                <dt class="col-sm-3">Cohesión</dt>
                <dd class="col-sm-9">
                    {{ $interaction->cohesion->name }}
                </dd>

                <dt class="col-sm-3">Símbolo</dt>
                <dd class="col-sm-9">
                    {{ $interaction->cohesion->symbol ?? '—' }}
                </dd>

                <dt class="col-sm-3">Descripción</dt>
                <dd class="col-sm-9">
                    {{ $interaction->cohesion->description ?? '—' }}
                </dd>

            </dl>

            <a href="{{ route('admin.interactions.edit', $interaction) }}"
               class="btn btn-warning">
                Editar
            </a>

            <a href="{{ route('admin.interactions.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

        </div>
    </div>

</x-admin-layout>
