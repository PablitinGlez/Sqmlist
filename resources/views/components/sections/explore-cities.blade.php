{{--
    Esta sección muestra tarjetas para explorar propiedades por ciudades destacadas.
--}}
<section class="py-16 bg-gray-50">
    <x-partials.container>
        <!-- Encabezado de la sección -->
        <div class="text-center mb-12">
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Compra, venta y renta de inmuebles en todo México.
            </p>
        </div>

        <!-- Grid de tarjetas de ciudades -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Tarjeta 1: Veracruz --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-[1.02]">
                <div class="h-40 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                   
                     <img src="{{ asset('images/veracruz.jpg') }}" alt="Veracruz" class="w-full h-full object-cover"> 
                   
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Veracruz</h3>
                    <a href="{{ route('properties.index', ['ubicacion' => 'Veracruz']) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center group" wire:navigate>
                        Ver propiedades
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-200 group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            {{-- Tarjeta 2: Ciudad de México --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-[1.02]">
                <div class="h-40 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                   
                    <img src="{{ asset('images/cdmx.jpg') }}" alt="Ciudad de México" class="w-full h-full object-cover"> 
                   
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Ciudad de México</h3>
                    <a href="{{ route('properties.index', ['ubicacion' => 'Ciudad de México']) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center group" wire:navigate>
                        Ver propiedades
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-200 group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            {{-- Tarjeta 3: Puebla --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-[1.02]">
                <div class="h-40 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                 
                     <img src="{{ asset('images/puebla.jpg') }}" alt="Puebla" class="w-full h-full object-cover"> 
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Puebla</h3>
                    <a href="{{ route('properties.index', ['ubicacion' => 'Puebla']) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center group" wire:navigate>
                        Ver propiedades
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-200 group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </x-partials.container>
</section>
