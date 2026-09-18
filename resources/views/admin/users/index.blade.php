<x-admin-layout>

    <!-- Encabezado -->
    <x-slot name="content_header">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-right">Add Usuario</a>
        <h1 >Lista de Usuarios</h1>
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
                            <th>Correo</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email}}</td>
                                <td width="10px"><a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.users.edit', $user) }}">Editar</a></td>

                                <td width="10px">
                                    <Form action="{{ route('admin.users.destroy', $user) }}" method="POST">
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
    <div id="css">

    </div>

     <!-- javaScript -->
    <div id="js">

    </div>
</x-admin-layout>
