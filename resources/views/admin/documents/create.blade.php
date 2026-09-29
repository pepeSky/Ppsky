<x-admin-layout>

    <x-slot name="content_header">
        <h1>Registrar Documento</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.documents.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control"
                           required>

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="description"
                              class="form-control"
                              rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text"
                           name="location"
                           value="{{ old('location') }}"
                           class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    Registrar
                </button>

                <a href="{{ route('admin.documents.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
            </form>

        </div>
    </div>

</x-admin-layout>
