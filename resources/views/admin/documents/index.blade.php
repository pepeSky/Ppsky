<x-admin-layout>

    <x-slot name="content_header">
        <a href="{{ route('admin.documents.create') }}"
           class="btn btn-primary float-right">
            Agregar Documento
        </a>

        <h1>Documentación</h1>

        <p>
            Documentos registrados dentro del sistema de información del usuario.
        </p>
    </x-slot>

    @if (session('info'))
        <div class="alert alert-success">
            {{ session('info') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive p-0">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Ubicación</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($documents as $document)
                        <tr>
                            <td>{{ $document->id }}</td>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->description ?? '—' }}</td>
                            <td>{{ $document->location ?? '—' }}</td>

                            <td class="text-right">
                                <a href="{{ route('admin.documents.show', $document) }}"
                                   class="btn btn-sm btn-info">
                                    Ver
                                </a>

                                <a href="{{ route('admin.documents.edit', $document) }}"
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <form action="{{ route('admin.documents.destroy', $document) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar este documento?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No existen documentos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{ $documents->links() }}

</x-admin-layout>
