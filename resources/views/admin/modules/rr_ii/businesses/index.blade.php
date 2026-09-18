<x-admin-layout>

    <!-- Encabezado -->
    <div id="content-header">
        <h1 class="py-4">Unidades Negocio</h1>
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
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Add Business</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>CATEGORY</th>
                            <th>NAME</th>
                            <th>PRODUCCION</th>
                            <th>INSUMOS</th>
                            <th>PRODUCTIVIDAD</th>
                            <th>EFICACIA</th>
                            <th>EFICIENCIA</th>
                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($businesses as $business)
                            <tr>
                                <td>{{ $business->id }}</td>
                                <td>{{ $business->category }}</td>
                                <td>{{ $business->name }}</td>
                                <td>{{ $business->production }}</td>
                                <td>{{ $business->supplies }}</td>
                                <td>{{ $business->productivity }}</td>
                                <td>{{ $business->effectiveness }}</td>
                                <td>{{ $business->efficiency }}</td>
                                <td width="10px"><a class="btn btn-warning btn-sm"
                                    href="{{ route('admin.businesses.edit', $business) }}">Mostrar</a></td>

                                <td width="10px"><a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.businesses.edit', $business) }}">Editar</a></td>

                                <td width="10px">
                                    <Form action="{{ route('admin.businesses.destroy', $business) }}" method="POST">
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
