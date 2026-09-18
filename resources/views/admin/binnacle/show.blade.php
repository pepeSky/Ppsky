<x-admin-layout>

    <!-- Encabezado -->
    <div id="content-header">
        <h1 class="h-1 py-4">Lista de Publicaciones</h1>

        <p class="py-12 text-base">Las publicaciones corresponden a los hechos fisicos, actividades o registros que el usuario necesita guardar o almacenar, para ser utilizados con posterioridad y asi justificar o demostrar hechos o interacciones pasadas para la satisfaccion de sus necesidades; estos hechos deben estar asociados a un contexto que a nivel del programa corresponden a un modulo el cual se encarga de categorizar las acciones para administrarlas y la manera de ocurrencia que tiene este hecho ya sea preventiva, correctiva o emergencia</p>
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
                <a href="{{ route('admin.inputs.create') }}" class="btn btn-primary btn-sm">Add Input</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TIPO</th>
                            <th>CATEGORIA</th>
                            <th>NOMBRE</th>
                            <th>HECHO</th>
                            <th>ESTADO</th>
                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inputs as $input)
                            <tr>
                                <td>{{ $input->id }}</td>
                                <td>{{ $input->type }}</td>
                                <td>{{ $input->category }}</td>
                                <td>{{ $input->name }}</td>
                                <td>{{ $input->fact }}</td>
                                <td>{{ $input->status }}</td>

                                <td width="10px"><a class="btn btn-warning btn-sm"
                                    href="{{ route('admin.inputs.edit', $input) }}">Mostrar</a></td>

                                <td width="10px"><a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.inputs.edit', $input) }}">Editar</a></td>

                                <td width="10px">
                                    <Form action="{{ route('admin.inputs.destroy', $input) }}" method="POST">
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



