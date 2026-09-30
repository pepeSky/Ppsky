<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Entorno de trabajo') }}
                </h2>

                @if ($activeSystem)
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $activeSystem->identity->entity->name }}
                    </p>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Bienvenido, {{ auth()->user()->name }}
                    </h3>

                    @if ($activeSystem)
                        <p class="mt-2 text-gray-600">
                            Sistema activo:
                            <strong>
                                {{ $activeSystem->identity->entity->name }}
                            </strong>
                        </p>
                    @else
                        <p class="mt-2 text-gray-600">
                            No tienes un Sistema disponible.
                        </p>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
