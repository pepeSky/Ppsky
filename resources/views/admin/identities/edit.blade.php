<x-admin-layout>

    <x-slot name="content_header">
        <h1>Editar Identidad</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <div class="form-group">
                <label>Entidad</label>
                <p class="form-control-static">
                    {{ $identity->entity->name }}
                </p>
            </div>

            <form action="{{ route('admin.identities.update', $identity) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Identificación</label>

                    <input type="text"
                           name="identification"
                           value="{{ old('identification', $identity->identification) }}"
                           class="form-control">

                    @error('identification')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button class="btn btn-primary">
                    Guardar
                </button>

                <a href="{{ route('admin.identities.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</x-admin-layout>
