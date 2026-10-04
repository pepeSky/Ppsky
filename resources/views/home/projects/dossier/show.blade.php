<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Expediente del Proyecto
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold">
                        {{ $project->name }}
                    </h1>

                    <div class="mt-4 text-sm text-gray-600">
                        <div>
                            <strong>Sistema:</strong>
                            {{ $project->system?->identity?->entity?->name ?? 'Sin sistema' }}
                        </div>

                        <div>
                            <strong>Proyecto:</strong>
                            #{{ $project->id }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold mb-6">
                        Cronología
                    </h2>

                    @forelse ($project->records as $record)
                        <div class="border-l-4 border-gray-300 pl-5 pb-8">

                            <div class="text-sm text-gray-500">
                                {{ $record->occurred_at->format('d-m-Y H:i') }}
                            </div>

                            <div class="mt-1 font-semibold">
                                {{ $record->type }}
                            </div>

                            <div class="text-sm text-gray-600">
                                {{ $record->actor?->identity?->entity?->name ?? 'Sin actor' }}
                            </div>

                            <div class="mt-3 text-gray-800">
                                {{ $record->description }}
                            </div>

                        </div>
                    @empty
                        <p class="text-gray-500">
                            El proyecto todavía no posee registros.
                        </p>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
