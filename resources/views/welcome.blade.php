<x-welcome-layout>

    <div class="py-12">

        {{-- Inicio--}}
        <section class="mt-24">
            <h1 class="text-gray-600 text-center text-3xl mb-6">Bienvenido</h1>

            <div
                class="max-w-7 mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3  gap-x-6 gap-y-8">
                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-1.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Solicitudes</h1>
                    </header>
                    <p class="text-sm text-gray-500">Damos solución a los problemas que te acomplejen de manera aleatoria sin planes ni contratiempos, gestinamos solictudes de las organizaciones que necesiten apoyo en sus procesos externos.</p>
                </article>

                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-3.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Planes</h1>
                    </header>
                    <p class="text-sm text-gray-500">Desarrollamos a traves de planes para que cumplas tus objetivos, gestionamos la administración de los procesos de nuestros clientes .</p>
                </article>

                <article>
                    <figure>
                        <img class="rounded-xl h-36 w-full object-cover"
                            src="{{ asset('img/dashboard/contenido-4.jpg') }}" alt="">
                    </figure>
                    <header class="mt-2">
                        <h1 class="text-center text-xl text-gray-700">Requerimientos</h1>
                    </header>
                    <p class="text-sm text-gray-500">Gestionamos los requerimientos de las organizaciones que necesiten apoyo en sus procesos internos</p>
                </article>
            </div>
        </section>

        <section class="mt-24 bg-gray-700 py-12">
            <h1 class="text-center text-white text-3xl">
                Cuantificación del Analisis</h1>
            <p class=" text-center text-white">Dirígete a nuestro catálogo y busca la actividad o proyecto que necesites gestionar</p>

            <div class="flex justify-center mt-4">
                <a href="{{ route('modules.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
                    Concepto Desarrollo
                </a>
            </div>

        </section>


    </div>

</x-welcome-layout>
