<x-admin-layout>

    <!-- Encabezado -->
    <div id="content-header">
        <h1 class="py-4">Lista de Tareas</h1>
    </div>

    <!-- Contenido -->
    <div>
        @if (session('info'))
            <div class="alert alert-success">
                <strong>{{ session('info') }}</strong>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Add Task</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Task</th>
                            <th>Status</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                       {{-- @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td width="10px"><a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.categories.edit', $category) }}">Editar</a></td>

                                <td width="10px">
                                    <Form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </Form>
                                </td>
                            </tr>
                        @endforeach
                        --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Estilos -->
    <div id="css">

    </div>

     <!-- javaScript -->
    <div id="js">

    </div>
</x-admin-layout>
