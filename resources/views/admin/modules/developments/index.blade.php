@extends('adminlte::page')

@section('title', 'pepeSky - Administration')

@section('content_header')
    <h1>Developments List</h1>
@stop

@section('content')
    <p>Bienvenido al panel de Administración.</p>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th colspan="3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($developments as $development)
                    <tr>
                        <td>{{$development->id}}</td>
                        <td>{{$development->title}}</td>
                        <td width="10px"><a class="btn btn-warning btn-sm" href="#">Mostrar</a></td>
                        <td width="10px"><a class="btn btn-primary btn-sm" href="#">Editar</a></td>

                        <td width="10px" >
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
@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
