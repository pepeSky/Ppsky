<x-admin-layout>

    <x-slot name="content_header">
        <h1>Registrar Identidad</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.identities.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Entidad</label>

                    <select name="entity_id"
                            class="form-control"
                            required>

                        <option value="">Seleccione</option>

                        @foreach ($entities as $entity)
                            <option value="{{ $entity->id }}"
                                @selected(old('entity_id') == $entity->id)>

                                {{ $entity->name }}
                                — {{ $entity->nature->name }}

                            </option>
                        @endforeach

                    </select>

                    @error('entity_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Identificación</label>

                    <input type="text"
                           name="identification"
                           value="{{ old('identification') }}"
                           class="form-control">

                    @error('identification')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Registrar
                </button>

                <a href="{{ route('admin.identities.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</x-admin-layout>
