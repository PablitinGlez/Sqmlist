{{--
    Esta vista muestra una página de error 404 personalizada,
    ofreciendo opciones de navegación para que el usuario regrese al sitio.
--}}
<x-app-layout>
    <div class="min-h-screen flex items-center justify-center py-16">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[0.8fr_2.2fr] gap-16 items-center">
                <div class="flex justify-center lg:justify-end order-2 lg:order-1">
                    <div class="w-80 h-80 flex items-center justify-center">
                        <img src="{{ asset('images/404.svg') }}" alt="404 Error" class="w-[30rem] h-[30rem] md:w-[35rem] md:h-[35rem] lg:w-[40rem] lg:h-[40rem]">
                    </div>
                </div>

                <div class="text-center lg:text-left order-1 lg:order-2">
                    <h1 class="text-7xl lg:text-8xl font-bold text-gray-900 mb-4 leading-none">
                        404
                    </h1>

                    <h2 class="text-2xl lg:text-3xl font-semibold text-gray-900 mb-4">
                        ¡Ups! Algo salió mal.
                    </h2>

                    <p class="text-gray-600 mb-8 mx-auto lg:mx-0">
                        En Inmobiliaria no encontramos esta página, pero sí tu próximo hogar.
                    </p>

                    {{-- La barra de búsqueda ha sido eliminada de aquí --}}
                    {{-- <div class="mb-8 max-w-lg mx-auto lg:mx-0">
                        @livewire('hero-search')
                    </div> --}}

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        {{-- Enlaces directos a la página de propiedades con filtro de operación --}}
                        <a href="{{ route('properties.index', ['operacion' => 'sale']) }}" class="bg-white p-4 rounded-lg shadow-sm border hover:shadow-md transition-shadow text-center flex flex-col items-center" 
                            wire:navigate>
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                                <i class="fa-solid fa-house w-5 h-5 text-blue-600"></i>
                            </div>
                            <span class="text-sm text-gray-700">En Venta</span>
                        </a>

                        <a href="{{ route('properties.index', ['operacion' => 'rent']) }}" class="bg-white p-4 rounded-lg shadow-sm border hover:shadow-md transition-shadow text-center flex flex-col items-center"
                            wire:navigate>
                            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mb-2">
                                <i class="fa-solid fa-key w-5 h-5 text-teal-600"></i>
                            </div>
                            <span class="text-sm text-gray-700">En Renta</span>
                        </a>

                        {{-- Estos enlaces puedes ajustarlos según necesites, por ahora son placeholders --}}
                        <a href="{{ route('properties.index') }}" class="bg-white p-4 rounded-lg shadow-sm border hover:shadow-md transition-shadow text-center flex flex-col items-center" wire:navigate>
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                                <i class="fa-solid fa-fire w-5 h-5 text-purple-600"></i>
                            </div>
                            <span class="text-sm text-gray-700">Destacados</span>
                        </a>

                        <a href="{{ route('properties.index') }}" class="bg-white p-4 rounded-lg shadow-sm border hover:shadow-md transition-shadow text-center flex flex-col items-center"  wire:navigate>
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                                <i class="fa-solid fa-bolt w-5 h-5 text-green-600"></i>
                            </div>
                            <span class="text-sm text-gray-700">Nuevos</span>
                        </a>
                    </div>

                    <div class="flex justify-center lg:justify-start mx-auto lg:ml-0">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors"  wire:navigate>
                            <i class="fa-solid fa-arrow-left w-4 h-4 mr-2"></i>
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
