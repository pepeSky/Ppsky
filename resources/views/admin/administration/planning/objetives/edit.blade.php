<x-admin-layout>

    <div class="card card-outline card-primary">

        <div class="card-header">
            <h3 class="card-title">
                Editar Objetivo
            </h3>
        </div>

        <form method="POST"
              action="{{ route('admin.planning.objetives.update', [$plan, $objetive]) }}">

            @csrf
            @method('PUT')

            <div class="card-body">

                <div class="form-group">
                    <label>Clasificación</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $objetive->is_general
                               ? 'Objetivo general'
                               : ($objetive->parent_id === null
                                   ? 'Objetivo principal'
                                   : ($objetive->parent->parent_id === null
                                       ? 'Objetivo específico'
                                       : 'Objetivo cuántico')) }}"
                           readonly>
                </div>

                @if ($objetive->parent)

                    <div class="form-group">
                        <label>Objetivo superior</label>

                        <textarea class="form-control"
                                  rows="2"
                                  readonly>{{ $objetive->parent->description }}</textarea>
                    </div>

                @elseif (! $objetive->is_general)

                    <div class="form-group">
                        <label>Meta de Vida</label>

                        <select name="goal_id" class="form-control">
                            <option value="">Sin Meta asociada</option>

                            @foreach ($goals as $goal)
                                <option value="{{ $goal->id }}"
                                    @selected(old('goal_id', $objetive->goal_id) == $goal->id)>
                                    {{ $goal->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('goal_id')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                @endif

                <div class="form-group">
                    <label>Descripción del Objetivo</label>

                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              required>{{ old('description', $objetive->description) }}</textarea>

                    @error('description')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>

                <a href="{{ route('admin.planning.show', $plan) }}"
                   class="btn btn-default">
                    Cancelar
                </a>
            </div>

        </form>

    </div>

</x-admin-layout>
