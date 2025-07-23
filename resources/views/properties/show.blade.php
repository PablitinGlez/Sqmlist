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
    <div class="py-16 sm:py-16"> {{-- Ajustado el padding vertical --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- INICIA: Pila de Navegación (Breadcrumbs) Modificada --}}
            <nav class="flex items-center text-xs pt-4 sm:text-sm font-medium text-gray-500 mb-4 space-x-1 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
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
                    {{-- Alpine.js para la función de "Ver más/Ver menos" --}}
                    <div x-data="{ expanded: false, contentHeight: 0, showButton: false }"
                         x-init="$nextTick(() => {
                             contentHeight = $refs.descriptionContent.scrollHeight;
                             if (contentHeight > 96) { // Aprox. 4 líneas de texto (24px * 4 = 96px)
                                 showButton = true;
                             }
                         })"
                         class="bg-white p-6 rounded-lg border border-gray-100">
                        @php
                            $propertyTypeText = $property->propertyType ? $property->propertyType->name : 'Propiedad';
                            $operationText = match($property->operation_type) {
                                'sale' => 'Venta',
                                'rent' => 'Renta',
                                'both' => 'Venta y Renta',
                                default => 'Operación'
                            };
                        @endphp
                        {{-- CAMBIO: Aplicando text-sm en móvil y md:text-lg en escritorio --}}
                        <p class="text-sm md:text-lg font-semibold text-gray-800 mb-4">
                            Descripción de {{ $propertyTypeText }} en {{ $operationText }}
                            <br class="sm:hidden"> {{-- Salto de línea solo en móviles --}}
                        </p>
                        {{-- El texto de la descripción sigue siendo text-xs sm:text-sm --}}
                        <div class="text-gray-700 text-xs sm:text-sm leading-relaxed text-justify sm:text-left"
                             :class="{ 'max-h-24 overflow-hidden': !expanded && showButton, 'max-h-full': expanded }"
                             x-ref="descriptionContent"
                             style="transition: max-height 0.3s ease-out;">
                            @if($property->description)
                                {!! nl2br(e($property->description)) !!}
                            @else
                                <p>No hay descripción disponible para esta propiedad.</p>
                            @endif
                        </div>
                        <template x-if="showButton">
                            <button @click="expanded = !expanded"
                                    class="mt-4 text-blue-600 hover:text-blue-800 font-semibold text-xs sm:text-sm flex items-center">
                                <span x-text="expanded ? 'Ver menos' : 'Ver más'"></span>
                                <i class="ml-2 fas" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </template>
                    </div>

                 {{-- Sección de Características Principales --}}
<div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
    {{-- Header azul --}}
    <div class="bg-blue-500 p-2 sm:p-4">
        <h2 class="text-xs sm:text- font-semibold text-white">Características principales</h2>
    </div>

    <div class="p-6">
        @php
            $generalFeatures = $property->featureValues->filter(function ($featureValue) {
                return $featureValue->feature &&
                       $featureValue->feature->featureSection &&
                       $featureValue->feature->featureSection->name === 'Características Generales';
            })->sortBy(function ($featureValue) {
                return $featureValue->feature->order ?? 999;
            });

            $orderedSlugs = [
                'tipo_inmueble',
                'num_recamaras',
                'num_banos',
                'num_estacionamientos',
                'tamano_construccion_m2',
                'tamano_terreno_m2',
                'anos_antiguedad',
                'num_niveles',
            ];

            $displayFeatures = [];
            foreach ($orderedSlugs as $slug) {
                $feature = $generalFeatures->firstWhere('feature.slug', $slug);
                if ($feature) {
                    $displayFeatures[] = $feature;
                }
            }
            foreach ($generalFeatures as $feature) {
                if (!in_array($feature->feature->slug, $orderedSlugs)) {
                    $displayFeatures[] = $feature;
                }
            }
        @endphp

        @if(!empty($displayFeatures))
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-y-6 gap-x-8"> {{-- Aumentado gap-y de 4 a 6 para más separación --}}
                @foreach($displayFeatures as $featureValue)
                    @php
                        $iconClass = $featureValue->feature->icon ?? 'fas fa-question';
                        $featureName = $featureValue->feature->name ?? 'Característica Desconocida';
                        $featureValueDisplay = $featureValue->value ?? 'No especificado';

                        switch ($featureValue->feature->slug) {
                            case 'tamano_construccion_m2':
                                $featureName = 'Área construida';
                                break;
                            case 'tamano_terreno_m2':
                                $featureName = 'Área terreno';
                                break;
                            case 'num_niveles':
                                $featureName = 'Plantas';
                                break;
                            case 'anos_antiguedad':
                                $featureName = 'Antigüedad';
                                break;
                            case 'num_recamaras':
                                $featureName = 'Recámaras';
                                break;
                            case 'num_banos':
                                $featureName = 'Baños';
                                break;
                            case 'num_estacionamientos':
                                $featureName = 'Estacionamiento';
                                break;
                            case 'tipo_inmueble':
                                $featureName = 'Tipo de inmueble';
                                break;
                        }

                       
                    if ($featureValue->feature->slug === 'anos_antiguedad') {
    $featureValueDisplay = ucfirst($featureValueDisplay);
 
    if (strtolower($featureValueDisplay) !== 'nuevo') {
        $featureValueDisplay .= ' años';
    }
} elseif (in_array($featureValue->feature->slug, ['tamano_construccion_m2', 'tamano_terreno_m2'])) {
    if (is_numeric($featureValue->value)) {
        $featureValueDisplay = number_format($featureValue->value, 0) . ' m²';
    }
} elseif ($featureValue->feature->slug === 'tipo_inmueble') {
    $featureValueDisplay = ucfirst($featureValueDisplay);
}
                    @endphp
                    <div class="flex flex-col items-start text-gray-700">
                        <div class="flex items-center mb-1">
                            <div class="flex-shrink-0 mr-2 text-gray-500 text-sm"> {{-- Reducido tamaño del icono --}}
                                <i class="{{ $iconClass }}"></i>
                            </div>
                            <div class="text-xs text-gray-500">{{ $featureName }}</div> {{-- Reducido de text-sm a text-xs --}}
                        </div>
                        <div class="text-xs text-gray-900">{{ $featureValueDisplay }}</div> {{-- Removido font-semibold y reducido de text-lg a text-base --}}
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 text-sm">No hay características principales especificadas para esta propiedad.</p>
        @endif
    </div>
</div>

{{-- Sección de Amenidades y Servicios con Tabs --}}
<div x-data="{ activeTab: 'amenities' }" class="bg-white rounded-lg border border-gray-100 overflow-hidden">
    {{-- Encabezado con Tabs --}}
    <div class="flex border-b border-gray-100">
        <button
            @click="activeTab = 'amenities'"
            :class="{ 'bg-blue-500 text-white': activeTab === 'amenities', 'bg-white text-gray-700': activeTab !== 'amenities' }"
            class="flex-1 py-3 px-4 text-start  transition-colors duration-200 ease-in-out focus:outline-none text-xs sm:text- font-semibold text-white"
        >
            Amenidades
        </button>
        <button
            @click="activeTab = 'services'"
            :class="{ 'bg-blue-500 text-white': activeTab === 'services', 'bg-white text-gray-700': activeTab !== 'services' }"
            class="flex-1 py-3 px-4 text-start text-xs sm:text- font-semibold text-white transition-colors duration-200 ease-in-out focus:outline-none"
        >
            Servicios
        </button>
    </div>

    {{-- Contenido de las Tabs --}}
    <div class="p-6">
        {{-- Contenido de Amenidades --}}
        <div x-show="activeTab === 'amenities'">
            @php
                $amenities = $property->featureValues->filter(function ($featureValue) {
                    return $featureValue->feature &&
                           $featureValue->feature->featureSection &&
                           $featureValue->feature->featureSection->slug === 'amenidades' &&
                           $featureValue->casted_value;
                })->sortBy(function ($featureValue) {
                    return $featureValue->feature->order ?? 999;
                });
            @endphp

            @if($amenities->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-y-4 gap-x-6">
                    @foreach($amenities as $featureValue)
                        <div class="flex items-center text-gray-700">
                            <div class="flex-shrink-0 mr-2 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="text-xs">
                                {{ $featureValue->feature->name ?? 'Amenidad desconocida' }}
                                @if ($featureValue->feature->data_type !== 'boolean' && $featureValue->casted_value)
                                    <span class="text-gray-900 font-medium">{{ $featureValue->casted_value }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-xs">No hay amenidades registradas para esta propiedad.</p>
            @endif
        </div>

        {{-- Contenido de Servicios --}}
        <div x-show="activeTab === 'services'" x-cloak>
            @php
                $services = $property->featureValues->filter(function ($featureValue) {
                    return $featureValue->feature &&
                           $featureValue->feature->featureSection &&
                           $featureValue->feature->featureSection->slug === 'servicios' &&
                           $featureValue->casted_value;
                })->sortBy(function ($featureValue) {
                    return $featureValue->feature->order ?? 999;
                });
            @endphp

            @if($services->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-y-4 gap-x-6">
                    @foreach($services as $featureValue)
                        <div class="flex items-center text-gray-700">
                            <div class="flex-shrink-0 mr-2 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="text-xs">
                                {{ $featureValue->feature->name ?? 'Servicio desconocido' }}
                                @if ($featureValue->feature->data_type !== 'boolean' && $featureValue->casted_value)
                                    <span class="text-gray-900 font-medium">{{ $featureValue->casted_value }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-xs">No hay servicios registrados para esta propiedad.</p>
            @endif
        </div>
    </div>
</div>
{{-- Sección del Mapa de Google Maps --}}
<div class="bg-white rounded-lg border border-gray-100 p-6 mt-6">
    <h2 class=" text-xs sm:text-  font-semibold text-gray-800 mb-4">Ubicación de la Propiedad</h2>
    
    @if($property->address && $property->address->latitude && $property->address->longitude)
        <div id="propertyMap" style="height: 250px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
    @else
        <p class="text-gray-600">No se encontraron coordenadas de ubicación para esta propiedad.</p>
    @endif
</div>

@push('scripts')
<script>
    function initPropertyMap() {
        const latitude = {{ $property->address->latitude ?? 'null' }};
        const longitude = {{ $property->address->longitude ?? 'null' }};

        // Verifica si las coordenadas son válidas antes de intentar renderizar el mapa
        if (latitude !== null && longitude !== null && latitude !== 0 && longitude !== 0) {
            const mapElement = document.getElementById('propertyMap');
            
            // Asegúrate de que el elemento del mapa exista y tenga dimensiones
            if (mapElement) {
                const mapOptions = {
                    center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                    zoom: 16,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                    zoomControl: true,
                    gestureHandling: 'cooperative',
                    clickableIcons: false,
                    styles: []
                };

                const map = new google.maps.Map(mapElement, mapOptions);

                new google.maps.Marker({
                    position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                    map: map,
                    title: 'Ubicación de la Propiedad',
                    icon: {
                        url: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="%231E90FF" class="icon icon-tabler icons-tabler-filled icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" /></svg>',
                        scaledSize: new google.maps.Size(20, 20)
                    }
                });
            }
        }
    }
</script>
{{-- CORREGIDO: Cambiado services.Maps.api_key por services.google_maps.api_key --}}
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initPropertyMap"></script>
@endpush


<div class="bg-white rounded-lg border border-gray-100 p-4 sm:p-6 mt-6">
    <h2 class="text-sm sm:text-base font-semibold text-gray-800 mb-4">Datos del Anunciante</h2>
    
    @if($property->user) {{-- Aseguramos que la propiedad tenga un usuario asociado --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex items-start space-x-3 sm:space-x-4 flex-1">
                {{-- Imagen de Perfil del Anunciante --}}
                <img class="h-16 w-16 sm:h-20 sm:w-20 rounded-full object-cover flex-shrink-0" 
                     src="{{ $property->user->profile_photo_url }}" 
                     alt="{{ $property->user->name }}">
                
                <div class="flex-1 min-w-0">
                    {{-- Tipo de Usuario (Agente, Dueño Directo, Inmobiliaria) en un Badge --}}
                    @php
                        $businessType = $property->user->getBusinessTypeLabel();
                        $badgeClass = '';
                        if ($property->user->isAgent()) {
                            $badgeClass = 'bg-blue-100 text-blue-800'; // Estilo para Agente
                        } elseif ($property->user->isOwner()) {
                            $badgeClass = 'bg-green-100 text-green-800'; // Estilo para Dueño Directo
                        } elseif ($property->user->isRealEstateCompany()) {
                            $badgeClass = 'bg-purple-100 text-purple-800'; // Estilo para Inmobiliaria
                        }
                    @endphp
                    
                    @if($businessType)
                        <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium {{ $badgeClass }}">
                            {{ $businessType }}
                        </span>
                    @endif
                    
                    {{-- Nombre del Usuario/Anunciante --}}
                    <h3 class="mt-2 ml-2 text-sm sm:text-base font-normal text-gray-900 truncate">{{ $property->user->name }}</h3>
                    
                    {{-- Biografía del Anunciante (Comentado por ahora) --}}
                    {{--
                    @if($property->user->profileDetails && $property->user->profileDetails->biography)
                        <p class="mt-2 text-gray-600">
                            {{ $property->user->profileDetails->biography }}
                        </p>
                    @endif
                    --}}
                </div>
            </div>
            
            {{-- INDICADOR DE USUARIO CERTIFICADO / CONFIABLE --}}
            <div class="flex justify-start ml-2 sm:justify-end sm:ml-0 mt-2 sm:mt-0">
                <button class="verified-btn">
                    <svg viewBox="0 0 576 512" height="1em" class="logoIcon">
                        <path d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6H426.6c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"></path>
                    </svg>
                    <span class="btn-text">Anunciante Verificado</span>
                </button>
            </div>
        </div>
        
    @else
        <p class="text-gray-600 text-sm sm:text-base">Información del anunciante no disponible.</p>
    @endif
</div>
</div>


                
                

                {{-- Columna Derecha: Formulario de Contacto --}}
                {{-- w-full para móviles, md:w-1/3 para la proporción --}}
                <div class="w-full md:w-1/3">
                    <div id="contact-form-section" class="bg-white p-6 rounded-lg border border-gray-100 ">

                        @livewire('contact-property-form', ['propertyId' => $property->id])
                    </div>
                </div>
            </div>
            {{-- FIN: Contenido principal de la propiedad y formulario de contacto --}}

        </div>
    </div>
</x-app-layout>




<style>
    /* Botón Verificado Responsive */
.verified-btn {
    /* Tamaños móvil */
    width: 150px;
    height: 28px;
    font-size: 0.6rem;
    
    /* Tamaños desktop */
    border: none;
    border-radius: 40px;
    background: linear-gradient(to right, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: rgb(121, 103, 3);
    font-weight: 600;
    cursor: pointer;
    position: relative;
    z-index: 2;
    transition-duration: 0.3s;
    background-size: 200% 200%;
    flex-shrink: 0;
}

/* Responsive para pantallas más grandes */
@media (min-width: 640px) {
    .verified-btn {
        width: 190px;
        height: 32px;
        font-size: 0.7rem;
        gap: 8px;
    }
}

@media (min-width: 768px) {
    .verified-btn {
        height: 34px;
        font-size: 0.75rem;
    }
}

.logoIcon {
    flex-shrink: 0;
}

.logoIcon path {
    fill: rgb(121, 103, 3);
}

.verified-btn:hover {
    transform: scale(0.95);
    transition-duration: 0.3s;
    animation: gradient 5s ease infinite;
    background-position: right;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Texto del botón responsive */
.btn-text {
    white-space: nowrap;
    overflow: hidden;
}
</style>
