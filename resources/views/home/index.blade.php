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

                    @if ($systems->isNotEmpty())
                        <form
                            method="POST"
                            action="{{ route('app.system.active') }}"
                            class="mt-6"
                        >
                            @csrf

                            <label
                                for="system_id"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Sistema activo
                            </label>

                            <div class="mt-2 flex items-center gap-3">
                                <select
                                    id="system_id"
                                    name="system_id"
                                    class="block w-full max-w-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    @foreach ($systems as $system)
                                        <option
                                            value="{{ $system->id }}"
                                            @selected($activeSystem && $activeSystem->id === $system->id)
                                        >
                                            {{ $system->identity->entity->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900"
                                >
                                    Seleccionar
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="mt-4 text-gray-600">
                            No tienes un Sistema disponible.
                        </p>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
