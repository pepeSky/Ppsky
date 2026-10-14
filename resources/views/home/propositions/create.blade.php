<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva proposición
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('propositions.store') }}">
                        @csrf
                        @if($binnacle ?? null)<input type="hidden" name="binnacle_id" value="{{ $binnacle->id }}">@endif
                        @if($activeSystem ?? null)<input type="hidden" name="system_id" value="{{ $activeSystem->id }}"><p class="mb-4">Sistema: {{ $activeSystem->identity->entity->name }}</p>@endif

                        <div class="mb-6">
                            <label for="sentence"
                                   class="block font-medium text-sm text-gray-700">
                                Proposición
                            </label>

                            <textarea id="sentence"
                                      name="sentence"
                                      rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300"
                                      required
                                      autofocus>{{ old('sentence') }}</textarea>

                            @error('sentence')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ ($binnacle ?? null) ? route('admin.binnacles.show',$binnacle) : (($activeSystem ?? null) ? route('admin.posts.index') : route('propositions.index')) }}"
                               class="px-4 py-2 border rounded-md">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                Registrar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
