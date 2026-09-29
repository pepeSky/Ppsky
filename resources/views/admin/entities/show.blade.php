<x-admin-layout>

    <x-slot name="content_header">
        <h1>Editar Entidad</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.entities.update', $entity) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $entity->name) }}"
                           class="form-control"
                           required>
                </div>

                <div class="form-group">
                    <label>Código</label>
                    <input type="text"
                           name="code"
                           value="{{ old('code', $entity->code) }}"
                           class="form-control">
                </div>

                <div class="form-group">
                    <label>Naturaleza</label>

                    <select name="nature_id" class="form-control" required>
                        @foreach ($natures as $nature)
                            <option value="{{ $nature->id }}"
                                @selected(old('nature_id', $entity->nature_id) == $nature->id)>
                                {{ $nature->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($types->isNotEmpty())
                    <div class="form-group">
                        <label>Tipos</label>

                        @foreach ($types as $type)
                            <div class="form-check">
                                <input type="checkbox"
                                       name="types[]"
                                       value="{{ $type->id }}"
                                       class="form-check-input"
                                       id="type-{{ $type->id }}"
                                       @checked(
                                           in_array(
                                               $type->id,
                                               old('types', $entity->types->pluck('id')->all())
                                           )
                                       )>

                                <label class="form-check-label"
                                       for="type-{{ $type->id }}">
                                    {{ $type->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                <button class="btn btn-primary">
                    Guardar
                </button>

                <a href="{{ route('admin.entities.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</x-admin-layout>
