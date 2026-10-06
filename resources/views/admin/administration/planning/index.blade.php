<x-admin-layout>

    <x-slot name="content_header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="mb-0">Planning</h1>
                <small class="text-muted">
                    Administración de la planificación del Sistema
                </small>
            </div>

            @if ($activeSystem)
                <form method="POST"
                      action="{{ route('admin.system.active') }}"
                      class="form-inline mt-2 mt-md-0">
                    @csrf

                    <label for="system_id" class="mr-2 mb-0">
                        Sistema
                    </label>

                    <select id="system_id"
                            name="system_id"
                            class="form-control"
                            onchange="this.form.submit()">
                        @foreach ($systems as $system)
                            <option value="{{ $system->id }}"
                                @selected($activeSystem->id === $system->id)>
                                {{ $system->identity->entity->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="container-fluid">

        @if (session('info'))
            <div class="alert alert-success">
                {{ session('info') }}
            </div>
        @endif

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Planes del Sistema
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Sistema</th>
                                <th>Proyecto</th>
                                <th>Plan</th>
                                <th>Tipo</th>
                                <th>Inicio</th>
                                <th>Término</th>
                                <th>Metas</th>
                                <th>Objetivos</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($plans as $plan)
                                <tr>
                                    <td>
                                        {{ $plan->project->system->identity->entity->name }}
                                    </td>

                                    <td>{{ $plan->project->name }}</td>

                                    <td>
                                        <strong>{{ $plan->name }}</strong>
                                    </td>

                                    <td>
                                        @switch($plan->type)
                                            @case('strategic')
                                                Estratégico
                                                @break
                                            @case('tactical')
                                                Táctico
                                                @break
                                            @case('operational')
                                                Operativo
                                                @break
                                            @default
                                                {{ $plan->type }}
                                        @endswitch
                                    </td>

                                    <td>{{ $plan->starts_at?->format('d-m-Y') }}</td>
                                    <td>{{ $plan->ends_at?->format('d-m-Y') }}</td>
                                    <td>{{ $plan->goals->count() }}</td>
                                    <td>{{ $plan->objetives->count() }}</td>

                                    <td class="text-right">
                                        <a href="{{ route('admin.planning.show', $plan) }}"
                                           class="btn btn-sm btn-primary">
                                            Administrar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"
                                        class="text-center text-muted py-4">
                                        El Sistema activo no tiene planes registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</x-admin-layout>
