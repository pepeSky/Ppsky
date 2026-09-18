<div class="card">
    <div class="card-header">
        <input wire:model="search" class="form-control" placeholder="Ingrese el nombre de la actividad" autocomplete="off">
    </div>
    @if ($activities->count())
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ACTIVIDAD</th>
                        <th>ESTADO</th>
                        <th>FRECUENCIA</th>
                        <th colspan="3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>{{ $activity->name }}</td>
                            <td>{{ $activity->status }}</td>

                            <td width="10px"><a class="btn btn-warning btn-sm"
                                    href="{{ route('admin.activities.show', $activity) }}">Mostrar</a></td>

                            <td width="10px"><a class="btn btn-primary btn-sm"
                                    href="{{ route('admin.activities.edit', $activity) }}">Editar</a></td>

                            <td width="10px">
                                <Form action="{{ route('admin.activities.destroy', $activity) }}" method="POST">
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
            {{-- $posts->links() --}}
        </div>
    @else
        <strong class="card-body">No hay ningun registro</strong>
    @endif
</div>
