<x-admin-layout>

    <!-- Encabezado -->
    <x-slot name="content_header">

        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary float-right">Add Posts</a>
        <h1>Registros</h1>

        <p class="py-4 text-base">Los registros corresponden a las acciones que el
            usuario necesita guardar o almacenar para ser utilizados con posterioridad y asi justificar o demostrar
            hechos o interacciones pasadas para la satisfaccion de sus necesidades; estos hechos deben estar asociados a un contexto o proyecto que a nivel del programa corresponden a un modulo el cual se encarga de categorizar las acciones y la manera de ocurrencia que tienen estos ya sea preventiva, correctiva o emergencia</p>


    </x-slot>
    <!-- Contenido -->
    <div>

        @if (session('info'))
            <div class="alert alert-success">
                <strong>{{ session('info') }}</strong>
            </div>
        @endif
        @livewire('admin.posts-index')
    </div>


    <!-- Estilos -->
    <x-slot name="css">

    </x-slot>

    <!-- javaScript -->
    <x-slot name="js">

    </x-slot>
</x-admin-layout>
