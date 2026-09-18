<x-admin-layout>

    <!-- Encabezado -->
    <x-slot name="content_header">
        <h1>Proyecto de Vida</h1>
    </x-slot>

    <!-- Contenido -->
    <div class="container">
        <div class="grid grid-rows-3 gap-8">
            <div class="grid grid-cols-6">
                <div class="bg-red-400">Entidades</div>
                <div class="bg-blue-200">Interacciones</div>
                <div class="bg-blue-300">Retroalimentación</div>
                <div class="bg-blue-300">Procesos</div>
                <div class="bg-blue-300">Valorización</div>
                <div class="bg-blue-300">Indicadores</div>
            </div>
            <div class="grid-row-1 mt-12">Despliegue Selección Cuadro</div>
            <div class="grid-row-1 mt-12">Alertas Selección Despliegue</div>
        </div>
    </div>

    <!-- Estilos -->
    <x-slot name="css">

    </x-slot>

    <!-- javaScript -->
    <x-slot name="js">

    </x-slot>
</x-admin-layout>
