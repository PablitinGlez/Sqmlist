<x-app-layout>

    {{-- Sub-navegador/Barra de Acciones del Detalle --}}
    {{-- Ajuste del tamaño del padding vertical en móviles para un diseño más compacto --}}
    <div class="bg-white shadow-sm py-2 sm:py-4 border-b border-gray-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            {{-- Botón Regresar --}}
            <button onclick="window.history.back()"
                class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors duration-200 text-xs sm:text-sm font-semibold">
                <i class="fas fa-arrow-left mr-1 sm:mr-2"></i> <span class="hidden sm:inline">Regresar a la búsqueda</span>
                <span class="inline sm:hidden">Regresar</span>
            </button>

            {{-- Acciones Derecha --}}
            {{-- Ajustar space-x para móviles, y flex-nowrap para evitar saltos de línea --}}
            <div class="flex items-center space-x-2 sm:space-x-3 flex-nowrap">
                {{-- Botón Guardar (Componente Livewire de Favoritos) --}}
                @livewire('favorite-button-detail', ['property' => $property], key('detail-favorite-button-' . $property->id))

                {{-- Botón Compartir --}}
                <button id="shareButton"
                        class="inline-flex items-center px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-sm text-xs sm:text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
                        onclick="copyToClipboard('{{ url()->current() }}', this)">
                    <i class="fas fa-share-alt mr-0 sm:mr-2"></i>
                    <span class="hidden sm:inline">Compartir</span>
                </button>

                {{-- Botón Ver Teléfono --}}
                <a href="#contact-form-section"
                   class="hidden md:inline-flex items-center px-4 py-2 border border-blue-500 rounded-full shadow-sm text-xs md:text-sm font-medium text-blue-500 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-phone-alt mr-2"></i> Ver teléfono
                </a>

                {{-- Botón WhatsApp --}}
                @if($property->contact_whatsapp_number || ($property->user && $property->user->profileDetails && $property->user->profileDetails->whatsapp_number))
                    @php
                        $whatsappNumber = $property->contact_whatsapp_number ?? ($property->user->profileDetails->whatsapp_number ?? '');
                        $whatsappLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsappNumber) . '?text=' . urlencode('Hola, me interesa la propiedad: ' . ($property->title ?? 'esta propiedad') . ' - ' . route('properties.show', $property->slug ?? '#'));
                    @endphp
                    <a href="{{ $whatsappLink }}"
                        target="_blank"
                        class="hidden md:inline-flex items-center px-4 py-2 border border-transparent rounded-full shadow-sm text-xs md:text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Script para copiar al portapapeles --}}
    <script>
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i> ¡Copiado!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 2000);
            }).catch(function(err) {
                console.error('No se pudo copiar el texto: ', err);
            });
        }
        // Script para el scroll suave
        document.querySelector('a[href="#contact-form-section"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>

    {{-- Contenido principal de la página de detalles --}}
    <div class="py-12 sm:py-16"> {{-- Ajustado el padding vertical --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- INICIA: Pila de Navegación (Breadcrumbs) Modificada --}}
            <nav class="flex items-center text-xs sm:text-sm font-medium text-gray-500 mb-4 space-x-1 overflow-x-auto whitespace-nowrap pt-4" aria-label="Breadcrumb">
                {{-- Inicio - OCULTADO EN MÓVILES, VISIBLE EN SM Y SUPERIORES --}}
                <a wire:navigate href="{{ url('/') }}" class="hidden sm:inline text-blue-600 hover:text-blue-800">Inicio</a>
                {{-- Separador para Inicio - OCULTADO EN MÓVILES, VISIBLE EN SM Y SUPERIORES --}}
                <span class="hidden sm:inline mx-1 text-gray-400">/</span>

                {{-- Estado (solo pasa parámetro de estado) --}}
                @if($property->address->state_name)
                    @php
                        $stateParams = [
                            'ubicacion' => $property->address->state_name
                        ];
                    @endphp
                    <a wire:navigate
                        href="{{ route('properties.index', $stateParams) }}"
                        class="text-blue-600 hover:text-blue-800">
                        {{ $property->address->state_name }}
                    </a>
                    <span class="mx-1 text-gray-400">/</span>
                @endif

                {{-- Colonia/Municipio (pasa estado + colonia) --}}
                @if($property->address->neighborhood_name)
                    @php
                        $locationParts = [$property->address->state_name];
                        $locationParts[] = $property->address->neighborhood_name;
                        $neighborhoodParams = [
                            'ubicacion' => implode(',', $locationParts)
                        ];
                    @endphp
                    <a wire:navigate
                        href="{{ route('properties.index', $neighborhoodParams) }}"
                        class="text-blue-600 hover:text-blue-800">
                        {{ $property->address->neighborhood_name }}
                    </a>
                    <span class="mx-1 text-gray-400">/</span>
                @elseif($property->title)
                    {{-- Si no hay colonia, usamos el título de la propiedad si es descriptivo --}}
                    <span class="text-gray-700">{{ $property->title }}</span>
                    <span class="mx-1 text-gray-400">/</span>
                @endif

                {{-- Operación (pasa estado + colonia + operación) --}}
                @php
                    $locationParts = [];
                    if ($property->address->state_name) {
                        $locationParts[] = $property->address->state_name;
                    }
                    if ($property->address->neighborhood_name) {
                        $locationParts[] = $property->address->neighborhood_name;
                    }

                    $operationParams = [];
                    if (!empty($locationParts)) {
                        $operationParams['ubicacion'] = implode(',', $locationParts);
                    }
                    $operationParams['operacion'] = $property->operation_type;

                    $operationText = match($property->operation_type) {
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                        default => 'Operación'
                    };
                @endphp
                <a wire:navigate
                    href="{{ route('properties.index', $operationParams) }}"
                    class="text-blue-600 hover:text-blue-800">
                    {{ $operationText }}
                </a>

                <span class="mx-1 text-gray-400">/</span>

                {{-- Tipo de Propiedad (pasa estado + colonia + operación + tipo) --}}
                @if($property->propertyType)
                    @php
                        $locationParts = [];
                        if ($property->address->state_name) {
                            $locationParts[] = $property->address->state_name;
                        }
                        if ($property->address->neighborhood_name) {
                            $locationParts[] = $property->address->neighborhood_name;
                        }

                        $propertyTypeParams = [];
                        if (!empty($locationParts)) {
                            $propertyTypeParams['ubicacion'] = implode(',', $locationParts);
                        }
                        $propertyTypeParams['operacion'] = $property->operation_type;
                        $propertyTypeParams['tipo'] = $property->propertyType->slug;
                    @endphp
                    <a wire:navigate
                        href="{{ route('properties.index', $propertyTypeParams) }}"
                        class="text-blue-600 hover:text-blue-800">
                        {{ $property->propertyType->name }}
                    </a>
                @endif
            </nav>
            {{-- FIN: Pila de Navegación (Breadcrumbs) --}}

            {{-- INICIA: Badges de Tipo de Propiedad y Operación --}}
            <div class="flex items-center space-x-2 mt-4 mb-6">
                @if($property->propertyType)
                    {{-- Ajuste: px-2 py-0.5 para móviles, sm:px-3 sm:py-1 para PC --}}
                    <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-semibold bg-gray-100 text-gray-800">
                        {{ strtoupper($property->propertyType->name) }}
                    </span>
                @endif

                @php
                    $operationBadgeText = match($property->operation_type) {
                        'sale' => 'VENTA',
                        'rent' => 'RENTA',
                        'both' => 'VENTA Y RENTA',
                        default => 'OPERACIÓN'
                    };
                    $operationBadgeColor = match($property->operation_type) {
                        'sale' => 'bg-blue-100 text-blue-800',
                        'rent' => 'bg-green-100 text-green-800',
                        'both' => 'bg-purple-100 text-purple-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                @endphp
                {{-- Ajuste: px-2 py-0.5 para móviles, sm:px-3 sm:py-1 para PC --}}
                <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-semibold {{ $operationBadgeColor }}">
                    {{ $operationBadgeText }}
                </span>
            </div>
            {{-- FIN: Badges de Tipo de Propiedad y Operación --}}

            {{-- INICIA: Bloque de Información de la Propiedad (Dirección, Tipo, Precio) --}}
            {{-- Contenedor principal para la información, usando flexbox para alinear el precio a la derecha --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 sm:mb-8">
                <div class="sm:flex-grow">
                    {{-- Dirección Completa - MANTENER TAMAÑO ORIGINAL --}}
                    <p class="text-lg font-bold text-gray-900 mb-2">
                        @if($property->address->neighborhood_name) {{ $property->address->neighborhood_name }}@endif
                        @if($property->address->city_name), {{ $property->address->city_name }}@endif
                        @if($property->address->state_name), {{ $property->address->state_name }}@endif
                        @if($property->address->zip_code), C.P. {{ $property->address->zip_code }}@endif
                    </p>

                    {{-- Tipo de Propiedad + Operación + Estado - CAMBIADO: Ahora usa text-xs sm:text-sm igual que las migas de pan --}}
                    <p class="text-xs sm:text-sm text-gray-700">
                        @if($property->propertyType)
                            {{ $property->propertyType->name }}
                        @else
                            Propiedad
                        @endif
                        en
                        @php
                            echo match($property->operation_type) {
                                'sale' => 'Venta',
                                'rent' => 'Renta',
                                'both' => 'Venta y Renta',
                                default => 'Operación'
                            };
                        @endphp
                        en {{ $property->address->state_name ?? 'N/A' }}
                    </p>

                    {{-- Municipio, Estado + Enlace Ver Ubicación - CAMBIADO: Ahora usa text-xs sm:text-sm igual que las migas de pan --}}
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                        Municipio {{ $property->address->municipality_name ?? 'N/A' }}, {{ $property->address->state_name ?? 'N/A' }}
                        <br class="sm:hidden"> {{-- Salto de línea solo en móvil --}}
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold underline">Ver Ubicación</a>
                    </p>
                </div>

                {{-- Precio de la Propiedad --}}
                {{-- text-xl en móvil, sm:text-2xl, md:text-3xl --}}
                <div class="mt-4 sm:mt-0 sm:ml-6 flex-shrink-0">
                    <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 text-left sm:text-right">
                        @if($property->price)
                            ${{ number_format($property->price, 0, '.', ',') }} MXN
                        @else
                            Precio no disponible
                        @endif
                    </p>
                </div>
            </div>
            {{-- FIN: Bloque de Información de la Propiedad --}}

            {{-- INICIA: Galería de Imágenes --}}
            {{-- Aquí envolveremos la galería en un div x-data para Alpine.js --}}
            <div x-data="{ openModal: false, currentImage: '' }">
                @if($property->images->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-8">
                        {{-- Imagen Principal (ocupa 2 columnas y 2 filas) --}}
                        <div class="relative col-span-2 row-span-2 overflow-hidden rounded-lg cursor-pointer"
                             @click="openModal = true; currentImage = '{{ $property->images->first()->full_url }}'">
                            <img src="{{ $property->images->first()->full_url }}" alt="{{ $property->images->first()->alt_text }}" class="w-full h-full object-cover">
                            {{-- Overlay para cantidad de imágenes (fondo blanco sólido) --}}
                            <div class="absolute bottom-3 left-3 bg-white bg-opacity-80 text-gray-800 px-3 py-1.5 rounded-full text-xs font-semibold flex items-center">
                                <i class="fas fa-camera mr-1.5"></i> {{ $property->images->count() }}
                            </div>
                        </div>

                        {{-- Cuadrícula de 4 imágenes adicionales --}}
                        @foreach($property->images->skip(1)->take(4) as $index => $image)
                            <div class="relative overflow-hidden rounded-lg cursor-pointer {{ $loop->last && $property->images->count() > 5 ? 'flex items-center justify-center' : '' }}"
                                 @click="openModal = true; currentImage = '{{ $image->full_url }}'">
                                <img src="{{ $image->full_url }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover">
                                {{-- Botón "Ver todas las fotos" en la última imagen de la cuadrícula si hay más de 5 imágenes --}}
                                @if($loop->last && $property->images->count() > 5)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                       <button class="text-blue-600 rounded-full bg-white hover:bg-gray-100 transition-all font-semibold flex items-center
                                                       px-2 py-1 text-xs sm:px-4 sm:py-2 sm:text-sm"
                                               @click.stop="openModal = true; currentImage = '{{ $property->images->first()->full_url }}'">
                                            <i class="fas fa-images mr-1 sm:mr-2"></i>
                                            <span class="inline sm:hidden">Ver todo</span>
                                            <span class="hidden sm:inline">Ver todas las fotos</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-100 p-8 rounded-lg text-center text-gray-500 mb-8">
                        <i class="fas fa-image text-4xl mb-3"></i>
                        <p class="text-lg font-semibold">No hay imágenes disponibles para esta propiedad.</p>
                    </div>
                @endif
                {{-- FIN: Galería de Imágenes --}}

                {{-- INICIO: Modal de Galería de Fotos --}}
                <div x-show="openModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90 flex items-center justify-center p-4"
                     style="display: none;"
                     @click.away="openModal = false"
                     @keydown.escape.window="openModal = false">

                    <div class="relative w-full max-w-4xl max-h-full bg-transparent rounded-lg shadow-xl overflow-hidden">
                        {{-- Botón de Cierre --}}
                        <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 text-2xl"
                                @click="openModal = false">
                            <i class="fas fa-times-circle"></i>
                        </button>

                        {{-- Contenedor principal de la imagen del modal --}}
                        <div class="relative w-full h-[70vh] flex items-center justify-center">
                            <img :src="currentImage" alt="Imagen de propiedad" class="max-w-full max-h-full object-contain">

                            {{-- Controles de navegación de imágenes --}}
                            <button @click.stop="
                                        const images = {{ json_encode($property->images->pluck('full_url')) }};
                                        const currentIndex = images.indexOf(currentImage);
                                        currentImage = images[(currentIndex - 1 + images.length) % images.length];
                                    "
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-40 text-white p-3 rounded-full text-xl z-10 focus:outline-none">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click.stop="
                                        const images = {{ json_encode($property->images->pluck('full_url')) }};
                                        const currentIndex = images.indexOf(currentImage);
                                        currentImage = images[(currentIndex + 1) % images.length];
                                    "
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-40 text-white p-3 rounded-full text-xl z-10 focus:outline-none">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        {{-- Miniaturas navegables (opcional, pero mejora mucho la UX) --}}
                        @if($property->images->count() > 1)
                        <div class="mt-4 px-4 pb-4 grid grid-cols-5 md:grid-cols-8 lg:grid-cols-10 gap-2 overflow-x-auto">
                            @foreach($property->images as $image)
                                <img src="{{ $image->full_url }}"
                                     alt="{{ $image->alt_text }}"
                                     class="w-16 h-16 object-cover rounded-md cursor-pointer border-2"
                                     :class="{ 'border-blue-500': currentImage === '{{ $image->full_url }}', 'border-transparent hover:border-gray-400': currentImage !== '{{ $image->full_url }}' }"
                                     @click="currentImage = '{{ $image->full_url }}'">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                {{-- FIN: Modal de Galería de Fotos --}}
            </div>

            {{-- INICIO: Contenido principal de la propiedad y formulario de contacto --}}
            {{-- Usamos flexbox para las dos columnas principales --}}
            <div class="flex flex-col md:flex-row gap-8 mt-8">

                {{-- Columna Izquierda: Contenido de la Propiedad (Descripción, Especificaciones, etc.) --}}
                {{-- Flex-grow para que ocupe el espacio restante, y w-full para móviles --}}
                <div class="w-full md:w-2/3 flex-grow space-y-8"> {{-- md:w-2/3 para la proporción, space-y-8 para separar las secciones --}}

                    {{-- Sección de Descripción --}}
                    <div class="bg-white p-6 rounded-lg border border-gray-100">
                        @php
                            $propertyTypeText = $property->propertyType ? $property->propertyType->name : 'Propiedad';
                            $operationText = match($property->operation_type) {
                                'sale' => 'Venta',
                                'rent' => 'Renta',
                                'both' => 'Venta y Renta',
                                default => 'Operación'
                            };
                        @endphp
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Descripción de {{ $propertyTypeText }} en {{ $operationText }}</h2>
                        <div class="text-gray-700 leading-relaxed">
                            @if($property->description)
                                {!! nl2br(e($property->description)) !!}
                            @else
                                <p>No hay descripción disponible para esta propiedad.</p>
                            @endif
                        </div>
                    </div>

                    {{-- ESPACIO PARA FUTURAS SECCIONES (Especificaciones, Características, Amenidades, etc.) --}}
                    {{-- Por ejemplo:
                    <div class="bg-white p-6 rounded-lg border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Especificaciones</h3>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>Recámaras: {{ $property->bedrooms ?? 'N/A' }}</li>
                            <li>Baños: {{ $property->bathrooms ?? 'N/A' }}</li>
                            <li>Medios Baños: {{ $property->half_bathrooms ?? 'N/A' }}</li>
                            <li>Construcción: {{ $property->construction_size ? $property->construction_size . ' m²' : 'N/A' }}</li>
                            <li>Terreno: {{ $property->lot_size ? $property->lot_size . ' m²' : 'N/A' }}</li>
                        </ul>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Amenidades y Servicios</h3>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>Alberca</li>
                            <li>Estacionamiento</li>
                            <li>Seguridad 24/7</li>
                            <li>Jardín</li>
                        </ul>
                    </div>
                    --}}

                </div>

                {{-- Columna Derecha: Formulario de Contacto (Sticky) --}}
                {{-- md:w-1/3 para la proporción, self-start para mantenerlo arriba en el scroll --}}
                <div id="contact-form-section" class="w-full md:w-1/3 sticky md:top-24 self-start bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    {{-- Aquí se cargará tu componente Livewire del formulario de contacto --}}
                    {{-- Asegúrate de pasar el propertyId al componente Livewire si lo necesita --}}
                    @livewire('contact-property-form', ['propertyId' => $property->id])
                </div>
            </div>
            {{-- FIN: Contenido principal de la propiedad y formulario de contacto --}}

        </div>
    </div>

</x-app-layout>