<x-admin-layout>

    <x-slot name="content_header">
        <h1>Registrar Entidad</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.entities.store') }}" method="POST">
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
                    <label>Código</label>
                    <input type="text"
                           name="code"
                           value="{{ old('code') }}"
                           class="form-control">

                    @error('code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Naturaleza</label>

                    <select name="nature_id"
                            class="form-control"
                            required>

                        <option value="">Seleccione</option>

                        @foreach ($natures as $nature)
                            <option value="{{ $nature->id }}"
                                @selected(old('nature_id') == $nature->id)>
                                {{ $nature->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('nature_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
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
                                       @checked(in_array($type->id, old('types', [])))>

                                <label class="form-check-label"
                                       for="type-{{ $type->id }}">
                                    {{ $type->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    Registrar
                </button>

                <a href="{{ route('admin.entities.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</x-admin-layout>
