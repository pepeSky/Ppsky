<x-admin-layout>

    <!-- Encabezado -->
    <x-slot name="content_header">

        <a href="{{ route('admin.activities.create') }}" class="btn btn-primary float-right">Add Activity</a>
        <h1>Lista de Actividades</h1>

        <p class="py-4 text-base">Las actividades corresponden a los hechos fisicos, actividades o registros que el
            usuario necesita guardar o almacenar, para ser utilizados con posterioridad y asi justificar o demostrar
            hechos o interacciones pasadas para la satisfaccion de sus necesidades; estos hechos deben estar asociados a
            un contexto que a nivel del programa corresponden a un modulo el cual se encarga de categorizar las acciones
            para administrarlas y la manera de ocurrencia que tiene este hecho ya sea preventiva, correctiva o
            emergencia</p>


    </x-slot>
    <!-- Contenido -->
    <div>

        @if (session('info'))
            <div class="alert alert-success">
                <strong>{{ session('info') }}</strong>
            </div>
        @endif
        @livewire('admin.activity-index')
    </div>


    <!-- Estilos -->
    <x-slot name="css">

    </x-slot>

    <!-- javaScript -->
    <x-slot name="js">

    </x-slot>
</x-admin-layout>
