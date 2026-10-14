<x-admin-layout>
    <x-slot name="content_header">
        <a href="{{ route('admin.applications.create') }}" class="btn btn-primary float-right">Agregar aplicación</a>
        <h1>Aplicaciones</h1><p>Sistema: {{ $activeSystem->identity->display_name }}</p>
    </x-slot>

    <x-slot name="css"><style>
        .applications-table th, .applications-table td { vertical-align: middle; }
        .applications-table th:nth-child(1) { min-width: 10rem; }
        .applications-table th:nth-child(3) { min-width: 8rem; }
        .applications-table th:nth-child(4) { min-width: 11rem; }
        .applications-table .model-count, .applications-table .model-dictionary-link { white-space: nowrap; }
    </style></x-slot>
    @if (session('info')) <div class="alert alert-success">{{ session('info') }}</div> @endif

    <div class="card"><div class="card-body table-responsive">
        <table class="table table-striped applications-table">
            <thead><tr><th>Aplicación</th><th>Descripción</th><th>Plataformas compatibles</th><th>Modelos</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($applications as $application)
                    <tr>
                        <td><a href="{{ route('admin.applications.show', $application) }}">{{ $application->name }}</a>@if (!$application->system_id)<small class="d-block text-muted">Sin sistema asignado</small>@endif</td>
                        <td>{{ $application->description ?: '—' }}</td>
                        <td>
                            @forelse (($application->compatible_platforms ?? []) as $platform)
                                <span class="badge badge-info">{{ config('application_platforms.'.$platform, $platform) }}</span>
                            @empty
                                <span class="text-muted">Sin especificar</span>
                            @endforelse
                        </td>
                        <td>
                            @include('admin.applications.model-summary')
                        </td>
                        <td class="text-nowrap">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.applications.show', $application) }}">Ver</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.applications.edit', $application) }}">Modificar</a>

                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No hay aplicaciones registradas para este sistema.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $applications->links('pagination::bootstrap-4') }}
    </div></div>
</x-admin-layout>
