<x-admin-layout>

    <x-slot name="content_header">
        <h1>Editar Interacción</h1>
    </x-slot>

    <div class="card">
        <div class="card-body">

            <div class="form-group">
                <label>Actores</label>

                <p class="form-control-static">
                    <strong>
                        {{ $interaction->actorA->identity->entity->name }}
                    </strong>

                    &nbsp; ↔ &nbsp;

                    <strong>
                        {{ $interaction->actorB->identity->entity->name }}
                    </strong>
                </p>
            </div>

            <form action="{{ route('admin.interactions.update', $interaction) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Cohesión</label>

                    <select name="cohesion_id"
                            class="form-control"
                            required>

                        @foreach ($cohesions as $cohesion)
                            <option value="{{ $cohesion->id }}"
                                @selected(
                                    old(
                                        'cohesion_id',
                                        $interaction->cohesion_id
                                    ) == $cohesion->id
                                )>

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

                <button class="btn btn-primary">
                    Guardar
                </button>

                <a href="{{ route('admin.interactions.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</x-admin-layout>
