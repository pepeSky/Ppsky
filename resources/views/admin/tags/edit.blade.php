<x-admin-layout>

        <!-- Encabezado -->
        <x-slot name="content_header">
            <h1 class="py-4">Editar Etiqueta</h1>
        </x-slot>

        <!-- Contenido -->
        <div>
            @if (session('info'))
                <div class="alert alert-success">
                    <strong>{{ session('info') }}</strong>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    {!! Form::model($tag, ['route' => ['admin.tags.update', $tag], 'method' => 'put']) !!}

                    @include('admin.tags.partials.form')

                    {!! Form::submit('Actualizar Etiqueta', ['class' => 'btn btn-primary']) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <x-slot name="js">
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
        </x-slot>

</x-admin-layout>
