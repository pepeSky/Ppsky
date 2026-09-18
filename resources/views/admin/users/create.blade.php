<x-admin-layout>

    <!-- Encabezado -->
    <div id="content-header">
        <h1 class="py-4">Crear Categoría</h1>
    </div>

    <!-- Contenido -->
    <div>
        @if (session('info'))
            <div class="alert alert-success">
                <strong>{{ session('info') }}</strong>
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


                <div class="form-group">
                    {!! Form::label('model', 'Modelo') !!}
                    {!! Form::text('model', null, ['class' => 'form-control', 'placeholder' => ' Ingrese el nombre del modelo']) !!}
                </div>

                @error('model')
                    <span class="text-danger">{{ $message }}</span>
                @enderror


                {!! Form::submit('Crear Categoría', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

</x-admin-layout>
