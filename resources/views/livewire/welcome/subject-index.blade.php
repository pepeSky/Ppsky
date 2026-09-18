<div>
    <div class="bg-gray-200 py-4 my-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-6">
            <button class="focus:outline-none bg-white shadow h-12 px-4 rounded-lg text-gray-700 mr-4"
                wire:click="resetFilters">
                <i class="fas fa-archway mr-2"></i>
                Todas las materias
            </button>

            <!-- dropdown Ciencias-->
            <div x-data="{ open: false }" class="relative mr-4 max-w">
                <button
                    class="px-4 text-gray-700 block h-12 rounded-lg overflow-hidden focus:outline-none bg-white shadow"
                    x-on:click="open = true">
                    <i class="fas fa-tags text-sm mr-2"></i>
                    Ciencias
                    <i class="fas fa-angle-down text-sm ml-2"></i>
                </button>
                <!-- dropdown-->
                <div class="absolute rigth-0 w-80 mt-2 py-2 bg-white border rounded shadow-xl" x-show="open"
                    x-on:click.away="open = false">
                    @foreach ($sciences as $science)
                        <a class="cursor-pointer transition-colors duration-200 block px-4 py-2 text-normal text-gray-900 rounded hover:bg-purple-500 hover:text-white"
                            wire:click="$set('science_id',{{ $science->id }})"
                            x-on:click="open = false">{{ $science->name }}</a>
                    @endforeach

                </div>

            </div>

            <!-- dropdown Niveles-->
            <div x-data="{ open: false }" class="relative mr-4">
                <button
                    class="px-4 text-gray-700 block h-12 rounded-lg overflow-hidden focus:outline-none bg-white shadow"
                    x-on:click="open = true">
                    <i class="fas fa-tags text-sm mr-2"></i>
                    Niveles
                    <i class="fas fa-angle-down text-sm ml-2"></i>
                </button>
                <div class="absolute rigth-0 w-40 mt-2 py-2 bg-white border rounded shadow-xl" x-show="open"
                    x-on:click.away="open = false">
                    @foreach ($levels as $level)
                        <a class="cursor-pointer transition-colors duration-200 block px-4 py-2 text-normal text-gray-900 rounded hover:bg-purple-500 hover:text-white"
                            wire:click="$set('level_id',{{ $level->id }})"
                            x-on:click="open = false">{{ $level->name }}</a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <div class="mb-16">
        <section class="bg-cover" style="background-image: url({{ asset('img/welcome/subjects/subjects1.jpeg') }})">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-36">
                <div class="w-full md:w-3/4 lg:w-1/2">
                    <h1 class="text-white font-bold text-4xl">Las materias estan organizadas en 5 grandes grupos</h1>
                    <p class="text-green-600 text-lg mt-2 mb-4">Centro de Recursos para el Aprendizaje Avanzado, unete a
                        nuestro
                        equipo y potenciemos a los jovenes del mañana</p>

                    {{-- @livewire('blog.search-index') --}}
                </div>
            </div>
        </section>
    </div>


    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-8">

        {{--@foreach ($subjects as $subject)
                    <x-blog.subject-card :subject="$subject"/>
         @endforeach
         --}}
    </div>
    <div class="container py-8 my-8">
        {{ $subjects->links()}}
    </div>
</div>

</div>
