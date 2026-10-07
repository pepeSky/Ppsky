<x-admin-layout>

    <x-slot name="content_header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">Editar Planificación</h1>
                <small class="text-muted">
                    {{ $plan->name }}
                </small>
            </div>

            <a href="{{ route('admin.planning.show', $plan) }}"
               class="btn btn-default">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="container-fluid">

        <div class="card card-outline card-primary">

            <form method="POST"
                  action="{{ route('admin.planning.update', $plan) }}">

                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="form-group">
                        <label for="name">Plan</label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $plan->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                        >

                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type">Tipo</label>

                        <select
                            id="type"
                            name="type"
                            class="form-control @error('type') is-invalid @enderror"
                        >
                            <option value="operational"
                                @selected(old('type', $plan->type) === 'operational')>
                                Operativo
                            </option>

                            <option value="tactical"
                                @selected(old('type', $plan->type) === 'tactical')>
                                Táctico
                            </option>

                            <option value="strategic"
                                @selected(old('type', $plan->type) === 'strategic')>
                                Estratégico
                            </option>
                        </select>

                        @error('type')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="starts_at">
                                    Fecha de inicio
                                </label>

                                <input
                                    type="date"
                                    id="starts_at"
                                    name="starts_at"
                                    value="{{ old('starts_at', $plan->starts_at?->format('Y-m-d')) }}"
                                    class="form-control @error('starts_at') is-invalid @enderror"
                                >

                                @error('starts_at')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ends_at">
                                    Fecha de término
                                </label>

                                <input
                                    type="date"
                                    id="ends_at"
                                    name="ends_at"
                                    value="{{ old('ends_at', $plan->ends_at?->format('Y-m-d')) }}"
                                    class="form-control @error('ends_at') is-invalid @enderror"
                                >

                                @error('ends_at')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="form-group mb-0">
                        <label for="description">Descripción</label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $plan->description) }}</textarea>

                        @error('description')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit"
                            class="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-admin-layout>
