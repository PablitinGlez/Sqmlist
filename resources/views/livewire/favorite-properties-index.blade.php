<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden  sm:rounded-lg p-6 lg:p-8">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
               Mis Propiedades Favoritas
            </h1>

            @auth
                @if ($properties->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <dotlottie-wc
                            src="https://lottie.host/2572ce62-c9bb-4b3a-9b90-11759319e48a/Rvrd0SjHvN.lottie"
                            style="width: 250px; height: 250px;"
                            speed="1"
                            autoplay
                            loop
                        ></dotlottie-wc>
                        <p class="mt-6 text-xl font-semibold text-gray-700">
                            ¡Aún no tienes propiedades favoritas!
                        </p>
                        <p class="mt-2 text-gray-500 max-w-md">
                            Explora nuestras propiedades y haz clic en el corazón para añadir las que te gusten a esta lista.
                        </p>
                        <a href="{{ route('properties.index') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:navigate>
                            <i class="fas fa-search mr-2"></i> Explorar Propiedades
                        </a>
                    </div>
                @else
                    {{-- Opcional: Barra de búsqueda si deseas filtrar favoritos --}}
                    <div class="mb-6">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Buscar en tus favoritos..."
                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($properties as $property)
                            <x-property-card :property="$property" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $properties->links() }}
                    </div>
                @endif
            @else
                {{-- Mensaje para usuarios no autenticados --}}
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <i class="fas fa-lock text-gray-400 text-6xl mb-6"></i>
                    <p class="mt-6 text-xl font-semibold text-gray-700">
                        ¡Inicia sesión para ver tus propiedades favoritas!
                    </p>
                    <p class="mt-2 text-gray-500 max-w-md">
                        Crea una cuenta o inicia sesión para guardar las propiedades que más te interesen y acceder a ellas fácilmente.
                    </p>
                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
