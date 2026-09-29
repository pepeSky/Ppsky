<x-admin-layout>

    <x-slot name="content_header">
        <h1>Registrar Interacción</h1>

        <p>
            Establece una relación recíproca entre dos actores
            mediante una forma de cohesión.
        </p>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.interactions.store') }}"
                  method="POST">

                @csrf

                <div class="form-group">
                    <label>Primer actor</label>

                    <select name="actor_a_id"
                            class="form-control"
                            required>

                        <option value="">Seleccione</option>

                        @foreach ($actors as $actor)
                            <option value="{{ $actor->id }}"
                                @selected(old('actor_a_id') == $actor->id)>

                                {{ $actor->identity->entity->name }}
                                — {{ $actor->identity->identification }}

                            </option>
                        @endforeach

                    </select>

                    @error('actor_a_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="text-center my-3">
                    <strong>↕</strong>
                </div>

                <div class="form-group">
                    <label>Segundo actor</label>

                    <select name="actor_b_id"
                            class="form-control"
                            required>

                        <option value="">Seleccione</option>

                        @foreach ($actors as $actor)
                            <option value="{{ $actor->id }}"
                                @selected(old('actor_b_id') == $actor->id)>

                                {{ $actor->identity->entity->name }}
                                — {{ $actor->identity->identification }}

                            </option>
                        @endforeach

                    </select>

                    @error('actor_b_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Cohesión</label>

                    <select name="cohesion_id"
                            class="form-control"
                            required>

                        <option value="">Seleccione</option>

                        @foreach ($cohesions as $cohesion)
                            <option value="{{ $cohesion->id }}"
                                @selected(old('cohesion_id') == $cohesion->id)>

                                {{ $cohesion->name }}
                                ({{ $cohesion->symbol }})

                            </option>
                        @endforeach

                    </select>

                    @error('cohesion_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Registrar
                </button>

                <a href="{{ route('admin.interactions.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</x-admin-layout>
