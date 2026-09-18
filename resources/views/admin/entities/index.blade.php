<x-admin-layout>

    <!-- Encabezado -->
    <x-slot name="content_header">

        <a href="{{ route('admin.entities.create') }}" class="btn btn-primary float-right">Add Entidad</a>
        <h1>Lista de Entidades</h1>

        <p class="py-4 text-base">Las Entidades corresponden a los individuos y organizaciones, las cuales interactuan entre si para ir ingresando, ejecutando y respaldando las acciones que se realizan y poder valorizarlas, para que las acciones representen una valorización las entidades deben tener una personalidad juridica vigente y acreditada al momento de realizar la transacción.</p>


    </x-slot>
    <!-- Contenido -->
    <div>

        @if (session('info'))
            <div class="alert alert-success">
                <strong>{{ session('info') }}</strong>
            </div>
        @endif
        @livewire('admin.entity-index')
    </div>


    <!-- Estilos -->
    <x-slot name="css">

    </x-slot>

    <!-- javaScript -->
    <x-slot name="js">

    </x-slot>
</x-admin-layout>
