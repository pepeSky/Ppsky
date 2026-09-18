@extends('adminlte::page')

@section('title', 'pepeSky - Administration')

@section('content_header')
    <h1>List Economic Facts</h1>
@stop

@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.economic-facts.create') }}" class="btn btn-primary btn-sm">Add Fact</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="1">Id</th>
                        <th>Fact</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($facts as $fact)
                        <tr>
                            <td>{{ $fact->id }}</td>
                            <td>{{ $fact->fact }}</td>
                            <td width="10px"><a class="btn btn-primary btn-sm"
                                    href="{{ route('admin.economic-facts.edit', $fact) }}">Editar</a></td>

                            <td width="10px">
                                <Form action="{{ route('admin.economic-facts.destroy', $fact) }}" method="POST">
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
@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
