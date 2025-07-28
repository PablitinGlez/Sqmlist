<x-app-layout>
    <div class="bg-white shadow-sm py-2 sm:py-4 border-b border-gray-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
           <button onclick="window.history.back()"
    class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors duration-200 text-xs sm:text-sm font-semibold">
    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i> 
   
    <span class="hidden sm:inline">Regresar a la búsqueda</span>
 
    <span class="sm:hidden">Regresar</span>
</button>

            <div class="flex items-center space-x-2 sm:space-x-3 flex-nowrap">
                @livewire('favorite-button-detail', ['property' => $property], key('detail-favorite-button-' . $property->id))

                <button id="shareButton"
    class="inline-flex items-center px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-sm text-xs sm:text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
    onclick="copyToClipboard('{{ url()->current() }}', this)">
    <i class="fas fa-share-alt mr-0 sm:mr-2"></i>
  
    <span class="hidden sm:inline">Compartir</span>
</button>

                <a href="#contact-form-section"
                    class="hidden md:inline-flex items-center px-4 py-2 border border-blue-500 rounded-full shadow-sm text-xs md:text-sm font-medium text-blue-500 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-phone-alt mr-2"></i> Ver teléfono
                </a>

                @if (
                    $property->contact_whatsapp_number ||
                        ($property->user && $property->user->profileDetails && $property->user->profileDetails->whatsapp_number))
                    @php
                        $whatsappNumber =
                            $property->contact_whatsapp_number ??
                            ($property->user->profileDetails->whatsapp_number ?? '');
                        $whatsappLink =
                            'https://wa.me/' .
                            preg_replace('/[^0-9]/', '', $whatsappNumber) .
                            '?text=' .
                            urlencode(
                                'Hola, me interesa la propiedad: ' .
                                    ($property->title ?? 'esta propiedad') .
                                    ' - ' .
                                    route('properties.show', $property->slug ?? '#'),
                            );
                    @endphp
                    <a href="{{ $whatsappLink }}" target="_blank"
                        class="hidden md:inline-flex items-center px-4 py-2 border border-transparent rounded-full shadow-sm text-xs md:text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text, button) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i> ¡Copiado!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 2000);
            } catch (err) {
                console.error('No se pudo copiar el texto: ', err);
            } finally {
                document.body.removeChild(textarea);
            }
        }

        document.querySelector('a[href="#contact-form-section"]').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>

    <div class="py-16 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="flex items-center text-xs pt-4 sm:text-sm font-medium text-gray-500 mb-4 space-x-1 overflow-x-auto whitespace-nowrap"
                aria-label="Breadcrumb">
                <a wire:navigate href="{{ url('/') }}"
                    class="hidden sm:inline text-blue-600 hover:text-blue-800">Inicio</a>
                <span class="hidden sm:inline mx-1 text-gray-400">/</span>

                @if ($property->address->state_name)
                    @php
                        $stateParams = [
                            'ubicacion' => $property->address->state_name,
                        ];
                    @endphp
                    <a wire:navigate href="{{ route('properties.index', $stateParams) }}"
                        class="text-blue-600 hover:text-blue-800">
                        {{ $property->address->state_name }}
                    </a>
                    <span class="mx-1 text-gray-400">/</span>
                @endif

                @if ($property->address->neighborhood_name)
                    @php
                        $locationParts = [$property->address->state_name];
                        $locationParts[] = $property->address->neighborhood_name;
                        $neighborhoodParams = [
                            'ubicacion' => implode(',', $locationParts),
                        ];
                    @endphp
                    <a wire:navigate href="{{ route('properties.index', $neighborhoodParams) }}"
                        class="text-blue-600 hover:text-blue-800">
                        {{ $property->address->neighborhood_name }}
                    </a>
                    <span class="mx-1 text-gray-400">/</span>
                @elseif($property->title)
                    <span class="text-gray-700">{{ $property->title }}</span>
                    <span class="mx-1 text-gray-400">/</span>
                @endif

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

                    $operationText = match ($property->operation_type) {
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                        default => 'Operación',
                    };
                @endphp
                <a wire:navigate href="{{ route('properties.index', $operationParams) }}"
                    class="text-blue-600 hover:text-blue-800">
                    {{ $operationText }}
                </a>

                <span class="mx-1 text-gray-400">/</span>

                @if ($property->propertyType)
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
                    <a wire:navigate href="{{ route('properties.index', $propertyTypeParams) }}"
                        class="text-blue-600 hover:text-blue-800">
                        {{ $property->propertyType->name }}
                    </a>
                @endif
            </nav>

            <div class="flex items-center space-x-2 mt-4 mb-6">
                @if ($property->propertyType)
                    <span
                        class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-semibold bg-gray-100 text-gray-800">
                        {{ strtoupper($property->propertyType->name) }}
                    </span>
                @endif

                @php
                    $operationBadgeText = match ($property->operation_type) {
                        'sale' => 'VENTA',
                        'rent' => 'RENTA',
                        'both' => 'VENTA Y RENTA',
                        default => 'OPERACIÓN',
                    };
                    $operationBadgeColor = match ($property->operation_type) {
                        'sale' => 'bg-blue-100 text-blue-800',
                        'rent' => 'bg-green-100 text-green-800',
                        'both' => 'bg-purple-100 text-purple-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span
                    class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-semibold {{ $operationBadgeColor }}">
                    {{ $operationBadgeText }}
                </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 sm:mb-8">
                <div class="sm:flex-grow">
                    <p class="text-lg font-bold text-gray-900 mb-2">
                        @if ($property->address->neighborhood_name)
                            {{ $property->address->neighborhood_name }}
                        @endif
                        @if ($property->address->municipality_name)
                            , {{ $property->address->municipality_name }}
                        @endif
                        @if ($property->address->state_name)
                            , {{ $property->address->state_name }}
                        @endif
                        @if ($property->address->zip_code)
                            , C.P. {{ $property->address->zip_code }}
                        @endif
                    </p>

                    <p class="text-xs sm:text-sm text-gray-700">
                        @if ($property->propertyType)
                            {{ $property->propertyType->name }}
                        @else
                            Propiedad
                        @endif
                        en
                        @php
                            echo match ($property->operation_type) {
                                'sale' => 'Venta',
                                'rent' => 'Renta',
                                'both' => 'Venta y Renta',
                                default => 'Operación',
                            };
                        @endphp
                        en {{ $property->address->state_name ?? 'N/A' }}
                    </p>

                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                        Municipio {{ $property->address->municipality_name ?? 'N/A' }},
                        {{ $property->address->state_name ?? 'N/A' }}
                        <br>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold underline">Ver
                            Ubicación</a>
                    </p>
                </div>

                <div class="mt-4 sm:mt-0 sm:ml-6 flex-shrink-0">
                    <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 text-left sm:text-right">
                        @if ($property->price)
                            ${{ number_format($property->price, 0, '.', ',') }} MXN
                        @else
                            Precio no disponible
                        @endif
                    </p>
                </div>
            </div>

            <div x-data="{ openModal: false, currentImage: '' }">
                @if ($property->images->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-8">
                        <div class="relative col-span-2 row-span-2 overflow-hidden rounded-lg cursor-pointer"
                            @click="openModal = true; currentImage = '{{ $property->images->first()->full_url }}'">
                            <img src="{{ $property->images->first()->full_url }}"
                                alt="{{ $property->images->first()->alt_text }}" class="w-full h-full object-cover">
                            <div
                                class="absolute bottom-3 left-3 bg-white bg-opacity-80 text-gray-800 px-3 py-1.5 rounded-full text-xs font-semibold flex items-center">
                                <i class="fas fa-camera mr-1.5"></i> {{ $property->images->count() }}
                            </div>
                        </div>

                        @foreach ($property->images->skip(1)->take(4) as $index => $image)
                            <div class="relative overflow-hidden rounded-lg cursor-pointer {{ $loop->last && $property->images->count() > 5 ? 'flex items-center justify-center' : '' }}"
                                @click="openModal = true; currentImage = '{{ $image->full_url }}'">
                                <img src="{{ $image->full_url }}" alt="{{ $image->alt_text }}"
                                    class="w-full h-full object-cover">
                                @if ($loop->last && $property->images->count() > 5)
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <button
                                            class="text-blue-600 rounded-full bg-white hover:bg-gray-100 transition-all font-semibold flex items-center
                                                px-2 py-1 text-xs sm:px-4 sm:py-2 sm:text-sm"
                                            @click.stop="openModal = true; currentImage = '{{ $property->images->first()->full_url }}'">
                                            <i class="fas fa-images mr-1 sm:mr-2"></i>
                                            <span>Ver todo</span>
                                            <span>Ver todas las fotos</span>
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

                <div x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90 flex items-center justify-center p-4"
                    style="display: none;" @click.away="openModal = false" @keydown.escape.window="openModal = false">

                    <div
                        class="relative w-full max-w-4xl max-h-full bg-transparent rounded-lg shadow-xl overflow-hidden">
                        <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 text-2xl"
                            @click="openModal = false">
                            <i class="fas fa-times-circle"></i>
                        </button>

                        <div class="relative w-full h-[70vh] flex items-center justify-center">
                            <img :src="currentImage" alt="Imagen de propiedad"
                                class="max-w-full max-h-full object-contain">

                            <button
                                @click.stop="
                                    const images = {{ json_encode($property->images->pluck('full_url')) }};
                                    const currentIndex = images.indexOf(currentImage);
                                    currentImage = images[(currentIndex - 1 + images.length) % images.length];
                                "
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-40 text-white p-3 rounded-full text-xl z-10 focus:outline-none">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button
                                @click.stop="
                                    const images = {{ json_encode($property->images->pluck('full_url')) }};
                                    const currentIndex = images.indexOf(currentImage);
                                    currentImage = images[(currentIndex + 1) % images.length];
                                "
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-40 text-white p-3 rounded-full text-xl z-10 focus:outline-none">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        @if ($property->images->count() > 1)
                            <div
                                class="mt-4 px-4 pb-4 grid grid-cols-5 md:grid-cols-8 lg:grid-cols-10 gap-2 overflow-x-auto">
                                @foreach ($property->images as $image)
                                    <img src="{{ $image->full_url }}" alt="{{ $image->alt_text }}"
                                        class="w-16 h-16 object-cover rounded-md cursor-pointer border-2"
                                        :class="{ 'border-blue-500': currentImage === '{{ $image->full_url }}', 'border-transparent hover:border-gray-400': currentImage !== '{{ $image->full_url }}' }"
                                        @click="currentImage = '{{ $image->full_url }}'">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-8 mt-8">

                <div class="w-full md:w-2/3 flex-grow space-y-8">

                    <div x-data="{ expanded: false, contentHeight: 0, showButton: false }" x-init="$nextTick(() => {
                        contentHeight = $refs.descriptionContent.scrollHeight;
                        if (contentHeight > 96) {
                            showButton = true;
                        }
                    })"
                        class="bg-white p-6 rounded-lg border border-gray-100">
                        @php
                            $propertyTypeText = $property->propertyType ? $property->propertyType->name : 'Propiedad';
                            $operationText = match ($property->operation_type) {
                                'sale' => 'Venta',
                                'rent' => 'Renta',
                                'both' => 'Venta y Renta',
                                default => 'Operación',
                            };
                        @endphp
                        <p class="text-sm md:text-lg font-semibold text-gray-800 mb-4">
                            Descripción de {{ $propertyTypeText }} en {{ $operationText }}
                            <br>
                        </p>
                        <div class="text-gray-700 text-xs sm:text-sm leading-relaxed text-justify sm:text-left"
                            :class="{ 'max-h-24 overflow-hidden': !expanded && showButton, 'max-h-full': expanded }"
                            x-ref="descriptionContent" style="transition: max-height 0.3s ease-out;">
                            @if ($property->description)
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

                    <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
                        <div class="bg-blue-500 p-2 sm:p-4">
                            <h2 class="text-xs sm:text-base font-semibold text-white">Características principales</h2>
                        </div>

                        <div class="p-6">
                            @php
                                $generalFeatures = $property->featureValues
                                    ->filter(function ($featureValue) {
                                        return $featureValue->feature &&
                                            $featureValue->feature->featureSection &&
                                            $featureValue->feature->featureSection->name ===
                                                'Características Generales';
                                    })
                                    ->sortBy(function ($featureValue) {
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

                            @if (!empty($displayFeatures))
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-y-6 gap-x-8">
                                    @foreach ($displayFeatures as $featureValue)
                                        @php
                                            $iconClass = $featureValue->feature->icon ?? 'fas fa-question';
                                            $featureName = $featureValue->feature->name ?? 'Característica Desconocida';
                                            $featureValueDisplay = $featureValue->value ?? 'No fespecificado';

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
                                                    $featureValueDisplay .= '';
                                                }
                                            } elseif (
                                                in_array($featureValue->feature->slug, [
                                                    'tamano_construccion_m2',
                                                    'tamano_terreno_m2',
                                                ])
                                            ) {
                                                if (is_numeric($featureValue->value)) {
                                                    $featureValueDisplay =
                                                        number_format($featureValue->value, 0) . ' m²';
                                                }
                                            } elseif ($featureValue->feature->slug === 'tipo_inmueble') {
                                                $featureValueDisplay = ucfirst($featureValueDisplay);
                                            }
                                        @endphp
                                        <div class="flex flex-col items-start text-gray-700">
                                            <div class="flex items-center mb-1">
                                                <div class="flex-shrink-0 mr-2 text-gray-500 text-sm">
                                                    <i class="{{ $iconClass }}"></i>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $featureName }}</div>
                                            </div>
                                            <div class="text-xs text-gray-900">{{ $featureValueDisplay }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600 text-sm">No hay características principales especificadas para
                                    esta propiedad.</p>
                            @endif
                        </div>
                    </div>

                    <div x-data="{ activeTab: 'amenities' }" class="bg-white rounded-lg border border-gray-100 overflow-hidden">

                        <div class="flex border-b border-gray-100">
                            <button @click="activeTab = 'amenities'"
                                :class="{ 'bg-blue-500 text-white': activeTab === 'amenities', 'bg-white text-gray-700': activeTab !== 'amenities' }"
                                class="flex-1 py-3 px-4 text-start transition-colors duration-200 ease-in-out focus:outline-none text-xs sm:text-base font-semibold">
                                Amenidades
                            </button>
                            <button @click="activeTab = 'services'"
                                :class="{ 'bg-blue-500 text-white': activeTab === 'services', 'bg-white text-gray-700': activeTab !== 'services' }"
                                class="flex-1 py-3 px-4 text-start text-xs sm:text-base font-semibold transition-colors duration-200 ease-in-out focus:outline-none">
                                Servicios
                            </button>
                        </div>


                        <div class="p-6">

                            <div x-show="activeTab === 'amenities'">
                                @php
                                    $amenities = $property->featureValues
                                        ->filter(function ($featureValue) {
                                            return $featureValue->feature &&
                                                $featureValue->feature->featureSection &&
                                                $featureValue->feature->featureSection->slug === 'amenidades' &&
                                                $featureValue->casted_value;
                                        })
                                        ->sortBy(function ($featureValue) {
                                            return $featureValue->feature->order ?? 999;
                                        });
                                @endphp

                                @if ($amenities->isNotEmpty())
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-y-4 gap-x-6">
                                        @foreach ($amenities as $featureValue)
                                            <div class="flex items-center text-gray-700">
                                                <div class="flex-shrink-0 mr-2 text-green-500">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="text-xs">
                                                    {{ $featureValue->feature->name ?? 'Amenidad desconocida' }}
                                                    @if ($featureValue->feature->data_type !== 'boolean' && $featureValue->casted_value)
                                                        <span
                                                            class="text-gray-900 font-medium">{{ $featureValue->casted_value }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-600 text-xs">No hay amenidades registradas para esta propiedad.
                                    </p>
                                @endif
                            </div>


                            <div x-show="activeTab === 'services'" x-cloak>
                                @php
                                    $services = $property->featureValues
                                        ->filter(function ($featureValue) {
                                            return $featureValue->feature &&
                                                $featureValue->feature->featureSection &&
                                                $featureValue->feature->featureSection->slug === 'servicios' &&
                                                $featureValue->casted_value;
                                        })
                                        ->sortBy(function ($featureValue) {
                                            return $featureValue->feature->order ?? 999;
                                        });
                                @endphp

                                @if ($services->isNotEmpty())
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-y-4 gap-x-6">
                                        @foreach ($services as $featureValue)
                                            <div class="flex items-center text-gray-700">
                                                <div class="flex-shrink-0 mr-2 text-green-500">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="text-xs">
                                                    {{ $featureValue->feature->name ?? 'Servicio desconocido' }}
                                                    @if ($featureValue->feature->data_type !== 'boolean' && $featureValue->casted_value)
                                                        <span
                                                            class="text-gray-900 font-medium">{{ $featureValue->casted_value }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-600 text-xs">No hay servicios registrados para esta propiedad.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-100 p-6 mt-6">
                        <h2 class="text-sm sm:text-base font-semibold text-gray-800 mb-4">Ubicación de la Propiedad</h2>

                        @if ($property->address && $property->address->latitude && $property->address->longitude)
                            <div id="propertyMap"
                                style="height: 250px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                        @else
                            <p class="text-gray-600">No se encontraron coordenadas de ubicación para esta propiedad.
                            </p>
                        @endif
                    </div>

                    @push('scripts')
                        <script>
                            function initPropertyMap() {
                                const latitude = {{ $property->address->latitude ?? 'null' }};
                                const longitude = {{ $property->address->longitude ?? 'null' }};


                                if (latitude !== null && longitude !== null && latitude !== 0 && longitude !== 0) {
                                    const mapElement = document.getElementById('propertyMap');


                                    if (mapElement) {
                                        const mapOptions = {
                                            center: {
                                                lat: parseFloat(latitude),
                                                lng: parseFloat(longitude)
                                            },
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
                                            position: {
                                                lat: parseFloat(latitude),
                                                lng: parseFloat(longitude)
                                            },
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

                        <script async defer
                            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initPropertyMap">
                        </script>
                    @endpush


                    <div class="bg-white rounded-lg border border-gray-100 p-4 sm:p-6 mt-6">
                        <h2 class="text-sm sm:text-base font-semibold text-gray-800 mb-4">Datos del Anunciante</h2>

                        @if ($property->user)
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex items-start space-x-3 sm:space-x-4 flex-1">

                                    <img class="h-16 w-16 sm:h-20 sm:w-20 rounded-full object-cover flex-shrink-0"
                                        src="{{ $property->user->profile_photo_url }}"
                                        alt="{{ $property->user->name }}">

                                    <div class="flex-1 min-w-0">

                                        @php
                                            $businessType = $property->user->getBusinessTypeLabel();
                                            $badgeClass = '';
                                            if ($property->user->isAgent()) {
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                            } elseif ($property->user->isOwner()) {
                                                $badgeClass = 'bg-green-100 text-green-800';
                                            } elseif ($property->user->isRealEstateCompany()) {
                                                $badgeClass = 'bg-purple-100 text-purple-800';
                                            }
                                        @endphp

                                        @if ($businessType)
                                            <span
                                                class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium {{ $badgeClass }}">
                                                {{ $businessType }}
                                            </span>
                                        @endif


                                        <h3 class="mt-2 ml-2 text-sm sm:text-base font-normal text-gray-900 truncate">
                                            {{ $property->user->name }}</h3>



                                        @if ($property->user->profileDetails && $property->user->profileDetails->biography)
                                            <p class="mt-2 text-sm ml-2 text-gray-600">
                                                {{ $property->user->profileDetails->biography }}
                                            </p>
                                        @endif

                                    </div>
                                </div>

                                <div class="flex justify-start ml-2 sm:justify-end sm:ml-0 mt-2 sm:mt-0">
                                    <button class="verified-btn">
                                        <svg viewBox="0 0 576 512" height="1em" class="logoIcon">
                                            <path
                                                d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6H426.6c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z">
                                            </path>
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

                <div class="w-full md:w-1/3">
                    <div id="contact-form-section" class="bg-white p-6 rounded-lg border border-gray-100 ">
                        @livewire('contact-property-form', ['propertyId' => $property->id])
                    </div>
                </div>
            </div>

            <div class="mt-8 col-span-full">
                @php
                    $locationText = [];
                    if ($property->address->neighborhood_name) {
                        $locationText[] = $property->address->neighborhood_name;
                    }

                    if (
                        $property->address->municipality_name &&
                        !in_array($property->address->municipality_name, $locationText)
                    ) {
                        $locationText[] = $property->address->municipality_name;
                    }
                    if ($property->address->state_name && !in_array($property->address->state_name, $locationText)) {
                        $locationText[] = $property->address->state_name;
                    }
                    $displayLocation = implode(', ', $locationText);

                    $operationTextForTitle = match ($property->operation_type) {
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                        default => 'Operación',
                    };

                    $morePropertiesParams = [];
                    if ($property->address->state_name) {
                        $morePropertiesParams['ubicacion'] = $property->address->state_name;
                        if ($property->address->municipality_name) {
                            $morePropertiesParams['ubicacion'] .= ',' . $property->address->municipality_name;
                        } elseif ($property->address->neighborhood_name) {
                            $morePropertiesParams['ubicacion'] .= ',' . $property->address->neighborhood_name;
                        }
                    }
                    $morePropertiesParams['operacion'] = $property->operation_type;
                    $morePropertiesUrl = route('properties.index', $morePropertiesParams);
                @endphp

                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                    Más {{ $property->propertyType->name ?? 'Propiedades' }} en {{ $operationTextForTitle }} cerca de
                    {{ $displayLocation }}
                </h2>

                @if ($similarProperties->isNotEmpty())
                    <div class="relative">
                        <div x-data="{
                            swiper: null,
                            init() {
                                this.swiper = new Swiper(this.$refs.container, {
                                    loop: false,
                                    centeredSlides: false,
                                    allowTouchMove: true,
                                    simulateTouch: true,
                                    touchRatio: 1,
                                    touchAngle: 45,
                                    preventClicks: false,
                                    preventClicksPropagation: false,
                                    navigation: {
                                        nextEl: this.$refs.nextButton,
                                        prevEl: this.$refs.prevButton,
                                    },
                                    breakpoints: {
                                        0: {
                                            slidesPerView: 1.1,
                                            spaceBetween: 16,
                                        },
                                        640: {
                                            slidesPerView: 2.1,
                                            spaceBetween: 20,
                                        },
                                        768: {
                                            slidesPerView: 3,
                                            spaceBetween: 24,
                                        },
                                        1024: {
                                            slidesPerView: 4,
                                            spaceBetween: 24,
                                        }
                                    }
                                });
                            }
                        }" x-init="init()" class="relative">
                            <div class="swiper-container overflow-hidden" x-ref="container">
                                <div class="swiper-wrapper">
                                    @foreach ($similarProperties as $similarProperty)
                                        <div class="swiper-slide h-auto">

                                            @include('components.property-card', [
                                                'property' => $similarProperty,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button @click="swiper.slidePrev()" x-ref="prevButton"
                                class="absolute top-1/2 -left-4 z-30 -translate-y-1/2 bg-white rounded-full shadow-md p-2 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-blue-500 hidden md:flex items-center justify-center">
                                <i class="fas fa-chevron-left text-gray-700"></i>
                            </button>
                            <button @click="swiper.slideNext()" x-ref="nextButton"
                                class="absolute top-1/2 -right-4 z-30 -translate-y-1/2 bg-white rounded-full shadow-md p-2 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-blue-500 hidden md:flex items-center justify-center">
                                <i class="fas fa-chevron-right text-gray-700"></i>
                            </button>
                        </div>
                    </div>


                    <div class="text-center mt-8">
                        <a href="{{ $morePropertiesUrl }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-xs sm:text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ver más propiedades en {{ $displayLocation }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 px-4">
                        <div class="text-start w-full">
                            <p class="text-xs sm:text-sm text-gray-600 mb-2">No encontramos propiedades similares en
                                {{ $displayLocation }}.</p>
                            <p class="text-xs sm:text-sm text-gray-500 mb-4">Intenta buscar en una ubicación más amplia
                                para ver más opciones.</p>
                        </div>
                        <div class="flex justify-center w-full">
                            <dotlottie-wc
                                src="https://lottie.host/aaca1413-4cbd-491a-bc04-33cff77eb212/SbaFy5Kw8Z.lottie"
                                style="width: 200px; height: 200px;" speed="1" autoplay loop></dotlottie-wc>
                        </div>


                        @if ($property->address->state_name)
                            <div class="mt-4 w-full flex justify-center">
                                <a href="{{ route('properties.index', ['ubicacion' => $property->address->state_name, 'operacion' => $property->operation_type]) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-blue-600 text-xs sm:text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Ver propiedades en {{ $property->address->state_name }}
                                    <i class="fas fa-search ml-2"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @push('scripts')
                <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
                <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

                <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>
            @endpush
        </div>
    </div>
</x-app-layout>

<style>
    .verified-btn {
        width: 150px;
        height: 28px;
        font-size: 0.6rem;
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

    .btn-text {
        white-space: nowrap;
        overflow: hidden;
    }
</style>
