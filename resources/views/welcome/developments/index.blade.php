<x-welcome-layout>

    <div class="py-12">
        <section class="bg-cover" style="background-image: url({{ asset('img/dashboard/banner-principal.jpeg') }})">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-36">
                <div class="w-full md:w-3/4 lg:w-1/2">
                    <h1 class="pt-6 text-black font-bold text-4xl">Desarrolla tus proyectos de manera customizada.</h1>
                    <p class="pt-6 text-black text-lg my-4">Modulos de control para la gestión de tus metas y objetivos.</p>

                    <div class="pt-2 relative mx-auto text-gray-600">
                        <input
                            class="w-full border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                            type="search" name="search" placeholder="Search">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded absolute right-0 top-0 mt-2">
                            Search
                        </button>

                    </div>
                </div>
            </div>
        </section>

        <section class="mt-24">
            <h1 class="text-gray-600 text-center text-3xl py-4">Contenido</h1>

            <p class="text-base text-center text-gray-500 py-10"> Un Desarrollo es un término que hace referencia desde su etimoligía procedente del griego a partir del prefijo des- que indica la inversión de una acción y del verbo enrollar. Por ello desarrollar debe entenderse como la acción contraria a enrollar, como desplegar o extender.

            A nivel funcional un Desarrollo debe comprenderse como la explicación metodológica a la solución de un problema puntual.</p>

            <div
                class="max-w-7 mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-8">
                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-1.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Desarrollo Web</h1>
                    </header>
                    <p class="text-sm text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus
                        possimus tenetur nulla sequi? Esse doloribus laboriosam neque, nemo ducimus necessitatibus!</p>
                </article>

                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-2.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Modúlos Gestión</h1>
                    </header>
                    <p class="text-sm text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus
                        possimus tenetur nulla sequi? Esse doloribus laboriosam neque, nemo ducimus necessitatibus!</p>
                </article>

                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-3.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Gestión Ciclo Vida</h1>
                    </header>
                    <p class="text-sm text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus
                        possimus tenetur nulla sequi? Esse doloribus laboriosam neque, nemo ducimus necessitatibus!</p>
                </article>

                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-4.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Desarrollo Personal</h1>
                    </header>
                    <p class="text-sm text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus
                        possimus tenetur nulla sequi? Esse doloribus laboriosam neque, nemo ducimus necessitatibus!</p>
                </article>
            </div>

        </section>

        <section class="mt-24 bg-gray-700 py-12">
            <h1 class="text-center text-white text-3xl">
                ¿No sabes por donde empezar?</h1>
            <p class=" text-center text-white">Dirígete a nuestro catálogo y busca la actividad o proyecto que necesites gestionar</p>

            <div class="flex justify-center mt-4">
                <a href="{{ route('modules') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
                    Concepto Desarrollo
                </a>
            </div>

        </section>

        <section class="mt-24 my-24">
            <h1 class="text-center text-3xl text-gray-600 ">Desarrollo Proyectos</h1>
            <p class="text-center text-gray-500 text-sm mb-6">Optimizando la Gestión de Recursos</p>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-8">
                @foreach ($developments as $development)
                    <article class="bg-white shadow-lg overflow-hidden">
                        <img class="h-36 w-full object-cover" src="{{ Storage::url($development->image->url) }}" alt="">

                        <div class="px-6 py-4">
                            <h1 class="text-xl text-gray-700 mb-2 leading-6">{{ Str::limit($development->title, '40') }}</h1>
                            <p class="text-gray-500 text-sm mb-2">Sup: Jose Riquelme</p>

                            <div class="flex">
                                <ul class="flex text-sm">
                                    <li class="mr-1 ">
                                        <i class="fas fa-star text-{{$development->rating >= 1 ? 'yellow' : 'gray'}}-400"></i>
                                    </li>
                                    <li class="mr-1 ">
                                        <i class="fas fa-star text-{{$development->rating >= 2 ? 'yellow' : 'gray'}}-400"></i>
                                    </li>
                                    <li class="mr-1 ">
                                        <i class="fas fa-star text-{{$development->rating >= 3 ? 'yellow' : 'gray'}}-400"></i>
                                    </li>
                                    <li class="mr-1 ">
                                        <i class="fas fa-star text-{{$development->rating >= 4 ? 'yellow' : 'gray'}}-400"></i>
                                    </li>
                                    <li class="mr-1 ">
                                        <i class="fas fa-star text-{{$development->rating == 5 ? 'yellow' : 'gray'}}-400"></i>
                                    </li>
                                </ul>

                                <p class="text-sm text-gray-500 ml-auto">
                                    <i class="fas fa-users"></i>
                                    ({{$development->users_count}})
                                </p>
                            </div>
                            <a href="{{route('developments.show', $development)}}"
                            class="block text-center w-full mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4">Más Información</a>
                        </div>

                    </article>
                @endforeach
            </div>
        </section>
    </div>

</x-welcome-layout>
