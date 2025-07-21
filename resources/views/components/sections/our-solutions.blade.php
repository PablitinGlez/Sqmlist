{{--
    Esta sección destaca las soluciones y beneficios que ofrece la plataforma
    mediante un diseño de tarjetas alternadas.
--}}
<section class="py-8 md:py-16 bg-white">
    <x-partials.container>
        <!-- Sección de Encabezado -->
        <div class="text-left mb-8 md:mb-12 md:text-center">
            <h2 class="text-lg md:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                Conoce las soluciones inmobiliarias de SQMLIST
            </h2>
            <p class="text-sm md:text-lg text-gray-600 md:max-w-3xl md:mx-auto">
                Un portal inmobiliario es más que un buscador de inmuebles.
            </p>
            <p class="text-sm md:text-lg text-gray-600 md:max-w-3xl md:mx-auto">
            ¡Explora todas nuestras herramientas!
            </p>
        </div>

        <!-- Grid de Tarjetas de Soluciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            {{-- Tarjeta 1: Busca Propiedades a tu Medida --}}
            <div class="relative bg-blue-50 border border-blue-200 rounded-xl shadow-sm p-4 md:p-6 flex flex-col justify-between items-start text-left overflow-hidden h-36 md:h-auto">
                <div>
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Busca Propiedades a tu Medida</h3>
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed">
                        Encuentra tu inmueble ideal con filtros avanzados.
                    </p>
                </div>
                {{-- Espacio para imagen SVG/figura --}}
                <div class="mt-2 md:mt-6 w-full h-12 md:h-24 flex justify-end items-end">
                    <img src="{{ asset('images/search.png') }}" alt="Icono de búsqueda de propiedades" class="w-auto h-full object-contain">
                </div>
            </div>

            {{-- Tarjeta 2: Organiza tus Propiedades Soñadas --}}
            <div class="relative bg-white border border-blue-200 rounded-xl shadow-sm p-4 md:p-6 flex flex-col justify-between items-start text-left overflow-hidden h-36 md:h-auto">
                <div>
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Organiza tus Propiedades Soñadas</h3>
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed">
                        Guarda y accede fácilmente a tus inmuebles preferidos.
                    </p>
                </div>
                {{-- Espacio para imagen SVG/figura --}}
                <div class="mt-2 md:mt-6 w-full h-12 md:h-24 flex justify-end items-end">
                    <img src="{{ asset('images/icono2.png') }}" alt="Icono de búsqueda de propiedades" class="w-auto h-full object-contain">
                </div>
            </div>

            {{-- Tarjeta 3: Anuncia tu Propiedad sin Costo --}}
            <div class="relative bg-blue-50 border border-blue-200 rounded-xl shadow-sm p-4 md:p-6 flex flex-col justify-between items-start text-left overflow-hidden h-36 md:h-auto">
                <div>
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Anuncia tu Propiedad sin Costo</h3>
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed">
                        Publica tu inmueble de forma sencilla y llega a más interesados.
                    </p>
                </div>
                {{-- Espacio para imagen SVG/figura --}}
                <div class="mt-2 md:mt-6 w-full h-12 md:h-24 flex justify-end items-end">
                    <img src="{{ asset('images/icono3.png') }}" alt="Icono de búsqueda de propiedades" class="w-auto h-full object-contain">
                </div>
            </div>

            {{-- Tarjeta 4: Asesoría Cuando la Necesites --}}
            <div class="relative bg-white border border-blue-200 rounded-xl shadow-sm p-4 md:p-6 flex flex-col justify-between items-start text-left overflow-hidden h-36 md:h-auto">
                <div>
                    <h3 class="text-sm md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">Asesoría Cuando la Necesites</h3>
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed">
                        Soporte especializado en tu búsqueda o publicación.
                    </p>
                </div>
                {{-- Espacio para imagen SVG/figura --}}
                <div class="mt-2 md:mt-6 w-full h-12 md:h-24 flex justify-end items-end">
                    <img src="{{ asset('images/icono4.png') }}" alt="Icono de búsqueda de propiedades" class="w-auto h-full object-contain">
                </div>
            </div>
        </div>
    </x-partials.container>
</section>