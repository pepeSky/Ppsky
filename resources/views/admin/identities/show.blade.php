<x-admin-layout>

    <x-slot name="content_header">
        <h1>{{ $identity->entity->name }}</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <dl class="row">

                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $identity->id }}</dd>

                <dt class="col-sm-3">Entidad</dt>
                <dd class="col-sm-9">
                    {{ $identity->entity->name }}
                </dd>

                <dt class="col-sm-3">Naturaleza</dt>
                <dd class="col-sm-9">
                    {{ $identity->entity->nature->name }}
                </dd>

                <dt class="col-sm-3">Identificación</dt>
                <dd class="col-sm-9">
                    {{ $identity->identification ?? '—' }}
                </dd>

                <dt class="col-sm-3">Actor</dt>
                <dd class="col-sm-9">
                    {{ $identity->actor ? 'Sí' : 'No' }}
                </dd>

            </dl>

            <a href="{{ route('admin.identities.edit', $identity) }}"
               class="btn btn-warning">
                Editar
            </a>

            <a href="{{ route('admin.identities.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

        </div>
    </div>

</x-admin-layout>
