<x-admin-layout>

    <!-- Encabezado -->
    <div id="content-header">
        <h1 class="py-4">Lista de Modulos</h1>

        <div class="py-4">
            <p>Los modulos de interacción corresponden a espacios de interacción virtual, los cuales tienen una funcion determinada estos estan compuestos de actores, procesos, herramientas y documentos, tienen un objetivo o funcionalidad determinada en la interacción.
            </p>

           </div>
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
                <a href="{{ route('admin.modules.create') }}" class="btn btn-primary btn-sm">Add Module</a>
            </div>


            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Modulo</th>
                            <th>Funcionalidad</th>

                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modules as $module)
                            <tr>
                                <td>{{ $module->id }}</td>
                                <td>{{ $module->module }}</td>
                                <td>{{ $module->objetive}}</td>
                                <td width="10px"><a class="btn btn-warning btn-sm"
                                    href="#">Mostrar</a></td>

                                <td width="10px"><a class="btn btn-primary btn-sm"
                                        href="#">Editar</a></td>

                                <td width="10px">
                                    <Form action="#" method="POST">
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
