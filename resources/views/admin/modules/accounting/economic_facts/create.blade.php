@extends('adminlte::page')

@section('title', 'pepeSky - Administration')

@section('content_header')
    <h1>Crear Categoría</h1>
@stop

@section('content')

@if (session('info'))
<div class="alert alert-success">
    <strong>{{session('info')}}</strong>
</div>
@endif
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'admin.categories.store']) !!}

            <div class="form-group">
                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => ' Ingrese el nombre de la categoría']) !!}
            </div>

            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', null, [
                    'class' => 'form-control',
                    'placeholder' => ' Ingrese el nombre del slug',
                    'readonly',
                ]) !!}
            </div>

            @error('slug')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            {!! Form::submit('Crear Categoría', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{ asset('vendor/jQuery-Plugin-stringToSlug-1.3/jquery.StringToSlug.min.js') }}">
        console.log('Hola pepe');
    </script>

    <script>
        $(document).ready(function() {
            $("#name").stringToSlug({
                setEvents: 'keyup keydown blur',
                getPut: '#slug',
                space: '-'
            });
        });
    </script>
@stop
