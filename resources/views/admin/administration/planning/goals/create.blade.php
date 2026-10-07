<x-admin-layout>

    <x-slot name="content_header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">Nueva Meta de Vida</h1>
                <small class="text-muted">{{ $plan->name }}</small>
            </div>

            <a href="{{ route('admin.planning.show', $plan) }}"
               class="btn btn-default">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="container-fluid">
        <div class="card card-outline card-success">

            <form method="POST"
                  action="{{ route('admin.planning.goals.store', $plan) }}">

                @csrf

                <div class="card-body">

                    <div class="form-group">
                        <label for="name">Meta</label>

                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="description">Descripción</label>

                        <textarea id="description"
                                  name="description"
                                  rows="6"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit"
                            class="btn btn-success">
                        Crear Meta
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-admin-layout>
