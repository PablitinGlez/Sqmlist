<section class="py-8 md:py-16">
    <x-partials.container>
        <div class="text-left mb-8 md:mb-12 md:text-center">
            <p class="text-sm md:text-lg text-gray-600 md:max-w-3xl md:mx-auto">
                Compra, venta y renta de inmuebles en todo México.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-[1.02]">
                <div class="h-32 md:h-40 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/veracruz.jpg') }}" alt="Veracruz" class="w-full h-full object-cover">
                </div>
                <div class="p-3 md:p-4">
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Veracruz</h3>
                    <a href="{{ route('properties.index', ['ubicacion' => 'Veracruz']) }}" class="text-blue-600 hover:atext-blue-700 font-medium flex items-center group text-xs md:text-base" wire:navigate>
                        Ver propiedades
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-200 group-hover:translate-x-1 text-xs md:text-sm"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-[1.02]">
                <div class="h-32 md:h-40 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/cdmx.jpg') }}" alt="Ciudad de México" class="w-full h-full object-cover">
                </div>
                <div class="p-3 md:p-6">
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Ciudad de México</h3>
                    <a href="{{ route('properties.index', ['ubicacion' => 'Ciudad de México']) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center group text-xs md:text-base" wire:navigate>
                        Ver propiedades
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-200 group-hover:translate-x-1 text-xs md:text-sm"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-[1.02]">
                <div class="h-32 md:h-40 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/puebla.jpg') }}" alt="Puebla" class="w-full h-full object-cover">
                </div>
                <div class="p-3 md:p-6">
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Puebla</h3>
                    <a href="{{ route('properties.index', ['ubicacion' => 'Puebla']) }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center group text-xs md:text-base" wire:navigate>
                        Ver propiedades
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-200 group-hover:translate-x-1 text-xs md:text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </x-partials.container>
</section>