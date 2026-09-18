<div class="card">
    <div class="card-header">
        <input wire:model="search" class="form-control" placeholder="Ingrese el nombre del post" autocomplete="off">
    </div>
@if ($posts->count())
<div class="card-body">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>CATEGORÍA</th>
                <th>ACCIÓN</th>
                <th>ESTADO</th>
                <th>REACCIONES</th>
                <th colspan="3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <th>{{ $post->category_id}}</th>
                    <td>{{ $post->action }}</td>
                    <td>{{ $post->status }}</td>
                    <td>4</td>

                    <td width="10px"><a class="btn btn-warning btn-sm"
                            href="{{ route('admin.posts.show', $post) }}">Mostrar</a></td>

                    <td width="10px"><a class="btn btn-primary btn-sm"
                            href="{{ route('admin.posts.edit', $post) }}">Editar</a></td>

                    <td width="10px">
                        <Form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
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
