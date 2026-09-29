@extends('adminlte::page')

@section('title', 'Actores')

@section('content_header')
    <h1>Actores</h1>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            {{ session('info') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <a href="{{ route('admin.actors.create') }}"
               class="btn btn-primary">
                Registrar actor
            </a>
        </div>

        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Entidad</th>
                        <th>Código</th>
                        <th>Identificación</th>
                        <th colspan="3"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($actors as $actor)
                        <tr>
                            <td>{{ $actor->id }}</td>

                            <td>
                                {{ $actor->identity->entity->name }}
                            </td>

                            <td>
                                {{ $actor->identity->entity->code }}
                            </td>

                            <td>
                                {{ $actor->identity->identification ?? '—' }}
                            </td>

                            <td width="10px">
                                <a class="btn btn-info btn-sm"
                                   href="{{ route('admin.actors.show', $actor) }}">
                                    Ver
                                </a>
                            </td>

                            <td width="10px">
                                <a class="btn btn-primary btn-sm"
                                   href="{{ route('admin.actors.edit', $actor) }}">
                                    Editar
                                </a>
                            </td>

                            <td width="10px">
                                <form action="{{ route('admin.actors.destroy', $actor) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="card-footer">
            {{ $actors->links() }}
        </div>

    </div>

@stop
