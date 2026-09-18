<x-admin-layout>

    <!-- Encabezado -->
    <div id="content-header">
        <h1 class="py-4">Editar Categoría</h1>
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
                {!! Form::model($category, ['route' => ['admin.categories.update', $category], 'method' => 'put']) !!}

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

                {!! Form::submit('Actualizar Categoría', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

     <!-- javaScript -->
    <div id="js">
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
    </div>
</x-admin-layout>
