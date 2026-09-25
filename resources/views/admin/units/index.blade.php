<x-admin-layout>
    <x-slot name="content_header">
        <a href="{{ route('admin.units.create') }}" class="btn btn-primary float-right">Agregar unidad</a>
        <h1>Unidades</h1>
    </x-slot>
    @if (session('info')) <div class="alert alert-success">{{ session('info') }}</div> @endif
    <div class="card"><div class="card-body table-responsive">
        <table class="table table-striped">
            <thead><tr><th>ID</th><th>Nombre</th><th>Símbolo</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td>{{ $unit->id }}</td><td>{{ $unit->name }}</td><td>{{ $unit->symbol }}</td>
                        <td class="text-nowrap">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.units.show', $unit) }}">Ver</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.units.edit', $unit) }}">Modificar</a>
                            <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta unidad?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No hay unidades registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $units->links('pagination::bootstrap-4') }}
    </div></div>
</x-admin-layout>
