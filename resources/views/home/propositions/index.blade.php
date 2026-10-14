<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Catálogo proposicional
            </h2>

            <a href="{{ route('propositions.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded-md">
                Nueva proposición
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-white shadow sm:rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    @if ($propositions->isEmpty())
                        <p class="text-gray-600">
                            No existen proposiciones registradas.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left">ID</th>
                                        <th class="px-4 py-3 text-left">Actor</th>
                                        <th class="px-4 py-3 text-left">Proposición</th>
                                        <th class="px-4 py-3 text-left">Registro</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($propositions as $proposition)
                                        <tr>
                                            <td class="px-4 py-3">
                                                P{{ $proposition->id }}
                                            </td>

                                            <td class="px-4 py-3">
                                                {{ $proposition->actor->identity->entity->name }}
                                            </td>

                                            <td class="px-4 py-3">
                                                {{ $proposition->sentence }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $proposition->created_at->format('d-m-Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $propositions->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
