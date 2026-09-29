<x-admin-layout>

    <x-slot name="content_header">
        <h1>Documento</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $document->id }}</dd>

                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $document->name }}</dd>

                <dt class="col-sm-3">Descripción</dt>
                <dd class="col-sm-9">{{ $document->description ?? '—' }}</dd>

                <dt class="col-sm-3">Ubicación</dt>
                <dd class="col-sm-9">{{ $document->location ?? '—' }}</dd>
            </dl>

            <a href="{{ route('admin.documents.edit', $document) }}"
               class="btn btn-warning">
                Editar
            </a>

            <a href="{{ route('admin.documents.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

        </div>
    </div>

</x-admin-layout>
