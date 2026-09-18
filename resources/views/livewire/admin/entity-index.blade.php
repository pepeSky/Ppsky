<div class="card">
    <div class="card-header">
        <input wire:model="search" class="form-control" placeholder="Ingrese el nombre de la entidad" autocomplete="off">
    </div>
@if ($entities->count())
<div class="card-body">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>CATEGORÍA</th>
                <th>NOMBRE</th>
                <th>CÓDIGO</th>
                <th colspan="3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entities as $entity)
                <tr>
                    <td>{{ $entity->id }}</td>
                    <td>{{ $entity->category}}</td>
                    <td>{{ $entity->name }}</td>
                    <td>{{ $entity->code }}</td>

                    <td width="10px"><a class="btn btn-warning btn-sm"
                            href="{{ route('admin.entities.show', $entity) }}">Mostrar</a></td>

                    <td width="10px"><a class="btn btn-primary btn-sm"
                            href="{{ route('admin.entities.edit', $entity) }}">Editar</a></td>

                    <td width="10px">
                        <Form action="{{ route('admin.entities.destroy', $entity) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </Form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    {{--$posts->links()--}}
</div>

@else
    <strong class="card-body">No hay ningun registro</strong>
@endif
</div>
