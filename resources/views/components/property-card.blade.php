{{-- Componente de Card con Modal de Contacto --}}
@props(['property'])

{{-- Contenedor principal con Alpine.js para manejar el estado del modal --}}
<div x-data="{ showContactModal: false }">
    {{-- Card de propiedad con altura fija --}}
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 h-[440px] flex flex-col">

        <!-- Componente Livewire de botón de favoritos -->
        @livewire('favorite-button', ['property' => $property], key($property->id))

        {{-- Enlace principal para la navegación a la página de detalles --}}
        <a href="{{ route('properties.show', $property->slug) }}" class="block h-full flex flex-col">

            <!-- Carrusel de imágenes - altura fija -->
            <div class="relative h-48 overflow-hidden flex-shrink-0" x-data="{
                currentSlide: 0,
                images: {{ json_encode($property->images->pluck('path')->map(fn($path) => asset('storage/' . $path))->toArray() ?: [asset('images/placeholder.png')]) }},
                get canGoNext() { return this.images.length > 1 && this.currentSlide < this.images.length - 1 },
                get canGoPrev() { return this.currentSlide > 0 },
                nextSlide() {
                    if (this.canGoNext) this.currentSlide++
                    else if (this.images.length > 1) this.currentSlide = 0
                },
                prevSlide() {
                    if (this.canGoPrev) this.currentSlide--
                    else if (this.images.length > 1) this.currentSlide = this.images.length - 1
                },
                goToSlide(index) {
                    this.currentSlide = index
                }
            }">
                <!-- Imágenes del carrusel -->
                <div class="relative w-full h-full">
                    <template x-for="(image, index) in images" :key="index">
                        <div x-show="currentSlide === index"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-full"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-full"
                             class="absolute inset-0">
                            <img :src="image" :alt="'Property Image ' + (index + 1)" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>

                <!-- Controles de navegación -->
                <template x-if="images.length > 1">
                    <div>
                        <button @click.stop="prevSlide()" x-show="canGoPrev"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 z-10 w-7 h-7 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200">
                            <i class="fas fa-chevron-left text-white text-xs"></i>
                        </button>
                        <button @click.stop="nextSlide()" x-show="canGoNext"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 z-10 w-7 h-7 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200">
                            <i class="fas fa-chevron-right text-white text-xs"></i>
                        </button>
                        <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1 z-10">
                            <template x-for="(image, index) in images" :key="index">
                                <button @click.stop="goToSlide(index)"
                                        class="w-1.5 h-1.5 rounded-full transition-all duration-200"
                                        :class="currentSlide === index ? 'bg-white' : 'bg-white/50'">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Contenido de la card - flex grow para ocupar espacio disponible -->
            <div class="p-3 pb-0 flex flex-col flex-grow">
                <!-- Badges/Etiquetas -->
                <div class="flex flex-wrap gap-1 mb-3 flex-shrink-0">
                    <span class="px-2 py-0.5 bg-gray-100 text-[0.65rem] font-semibold rounded-full uppercase">
                        {{ $property->propertyType->name ?? 'N/A' }}
                    </span>
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[0.65rem] font-semibold rounded-full uppercase">
                        {{ match($property->operation_type) {
                            'sale' => 'Venta',
                            'rent' => 'Renta',
                            'both' => 'Venta y Renta',
                            default => ucfirst($property->operation_type ?? 'N/A')
                        } }}
                    </span>
                </div>

                <!-- Precio -->
                <div class="text-lg font-bold text-gray-900 mb-2 flex-shrink-0">
                    ${{ number_format($property->price) }} MXN
                </div>

                <!-- Estadísticas/Características -->
                <div class="flex items-center gap-4 mb-3 text-gray-600 flex-shrink-0">
                    @php
                        $displayFeatures = [];
                        $featureSlugs = [
                            'num_recamaras', 'num_banos', 'num_medios_banos', 'tamano_construccion_m2',
                            'tamano_terreno_m2', 'num_estacionamientos', 'num_niveles', 'anos_antiguedad',
                            'has_alberca', 'has_gimnasio', 'has_cuarto_servicio', 'andenes',
                            'area_maniobras', 'tipo_electricidad', 'tipo_techo', 'vias_comunicacion',
                            'ancho_m', 'alto_m', 'profundidad_m', 'tamano_bodega_m2', 'uso_suelo',
                        ];

                        foreach ($featureSlugs as $slug) {
                            if (count($displayFeatures) >= 3) break;

                            $featureValue = $property->getFeatureValue($slug);
                            $featureDefinition = $property->featureValues->first(function($pfv) use ($slug) {
                                return $pfv->feature && $pfv->feature->slug === $slug;
                            })?->feature;

                            if ($featureDefinition) {
                                $icon = $featureDefinition->icon;
                                $unit = $featureDefinition->unit;
                                $name = $featureDefinition->name;
                                $displayValue = null;
                                $displayUnit = '';

                                if ($featureDefinition->data_type === 'boolean') {
                                    if ($featureValue === true) {
                                        $displayValue = $name;
                                        $displayUnit = '';
                                    } else {
                                        continue;
                                    }
                                } elseif (!is_null($featureValue) && $featureValue !== '') {
                                    if (!($featureValue === 0 && in_array($slug, ['num_recamaras', 'num_banos', 'num_medios_banos', 'num_estacionamientos', 'num_niveles', 'tamano_construccion_m2', 'tamano_terreno_m2', 'tamano_bodega_m2']))) {
                                        $displayValue = $featureValue;
                                        $displayUnit = $unit ? ' ' . $unit : '';
                                    } else {
                                        continue;
                                    }
                                } else {
                                    continue;
                                }

                                if ($icon) {
                                    $displayFeatures[] = [
                                        'icon' => $icon,
                                        'value' => $displayValue,
                                        'unit' => $displayUnit,
                                    ];
                                }
                            }
                        }
                    @endphp

                    @forelse($displayFeatures as $featureData)
                        <div class="flex items-center">
                            <i class="{{ $featureData['icon'] }} mr-1.5 text-gray-500 text-sm"></i>
                            <span class="text-xs">{{ $featureData['value'] }}{{ $featureData['unit'] }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-xs">No hay características destacadas para esta propiedad.</p>
                    @endforelse
                </div>

                <!-- Dirección - flex grow para ocupar espacio restante -->
                <div class="flex-grow flex items-start mb-2">
                    <p class="text-gray-900 text-xs text-left line-clamp-2">
                        {{ $property->address->full_address ?? 'Ubicación no disponible' }}
                    </p>
                </div>
            </div>
        </a> {{-- <--- ¡Cierre del enlace principal aquí! --}}

        {{-- Botones de acción - siempre en la parte inferior --}}
        <div class="p-3 mt-auto pt-4 flex gap-2 flex-shrink-0">
            @if($property->contact_whatsapp_number)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->contact_whatsapp_number) }}"
                    target="_blank"
                    class="flex-1 bg-green-500 text-white py-2 px-3 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors duration-200 flex items-center justify-center"
                    onclick="event.stopPropagation()">
                    <i class="fab fa-whatsapp mr-1.5 text-base"></i> WhatsApp
                </a>
            @endif

            {{-- Botón "Contactar" que abre el modal --}}
            <button type="button"
                    @click="showContactModal = true; $event.stopPropagation()"
                    class="flex-1 border border-blue-500 text-blue-500 py-2 px-3 rounded-lg font-semibold text-sm hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-envelope mr-1.5 text-base"></i> Contactar
            </button>
        </div>
    </div>

    {{-- Modal de Contacto --}}
    <div x-show="showContactModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
         @click.self="showContactModal = false">

        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

        {{-- Modal Content --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showContactModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="relative bg-white rounded-lg shadow-xl w-full max-w-md">

                {{-- Botón para cerrar el modal --}}
                <button @click="showContactModal = false"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                    <i class="fas fa-times text-xl"></i>
                </button>

                {{-- Contenido del modal: tu componente Livewire --}}
                <div class="p-6">
                    @livewire('contact-property-form', ['propertyId' => $property->id])
                </div>
            </div>
        </div>
    </div>
</div>
