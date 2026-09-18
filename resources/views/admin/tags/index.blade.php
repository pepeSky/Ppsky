<x-admin-layout>
       <!-- Encabezado -->
    <x-slot name="content_header">
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary float-right">Add Etiqueta</a>
        <h1>Lista de Etiquetas</h1>

    </x-slot>

    <!-- Contenido -->
    <div>
        @if (session('info'))
            <div class="alert alert-success">
                <strong>{{ session('info') }}</strong>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                            <tr>
                                <td>{{ $tag->id }}</td>
                                <td>{{ $tag->name }}</td>
                                <td width="10px"><a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.tags.edit', $tag) }}">Editar</a></td>

                                <td width="10px">
                                    <Form action="{{ route('admin.tags.destroy', $tag) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </Form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Estilos -->
    <x-slot name="css">

    </x-slot>

     <!-- javaScript -->
    <x-slot name="js">

    </x-slot>
</x-admin-layout>
