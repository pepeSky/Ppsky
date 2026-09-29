<x-admin-layout>

    <x-slot name="content_header">
        <h1>Editar Documento</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.documents.update', $document) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $document->name) }}"
                           class="form-control"
                           required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="description"
                              class="form-control"
                              rows="4">{{ old('description', $document->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text"
                           name="location"
                           value="{{ old('location', $document->location) }}"
                           class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    Guardar
                </button>

                <a href="{{ route('admin.documents.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
            </form>

        </div>
    </div>

</x-admin-layout>
