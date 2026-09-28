<x-admin-layout>
    <x-slot name="content_header">
        <a href="{{ route('admin.applications.create') }}" class="btn btn-primary float-right">Agregar aplicación</a>
        <h1>Aplicaciones</h1>
    </x-slot>

    @if (session('info')) <div class="alert alert-success">{{ session('info') }}</div> @endif

    <div class="card"><div class="card-body table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Nombre</th><th>Descripción</th><th>Ubicación</th><th>Modelos utilizados</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($applications as $application)
                    <tr>
                        <td><a href="{{ route('admin.applications.show', $application) }}">{{ $application->name }}</a></td>
                        <td>{{ $application->description ?: '—' }}</td>
                        <td>{{ $application->location ?: '—' }}</td>
                        <td>
                            @forelse ($application->models as $model)
                                <span class="badge badge-info">{{ $model->name }}</span>
                            @empty
                                <span class="text-muted">Sin modelos asociados</span>
                            @endforelse
                        </td>
                        <td class="text-nowrap">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.applications.show', $application) }}">Ver</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.applications.edit', $application) }}">Modificar</a>
                            <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('¿Eliminar esta aplicación?')">Eliminar</button>
                            </form>
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
