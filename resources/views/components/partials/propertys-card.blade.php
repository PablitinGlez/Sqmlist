@props(['property'])

<div
    class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
    onclick="window.location.href='{{ route('properties.show', $property->slug) }}'"
>
    <button class="absolute top-3 right-3 z-20 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200"
        onclick="event.stopPropagation()">
        <i class="far fa-heart text-white text-base hover:text-red-400 transition-colors"></i>
    </button>

    <div class="relative h-48 overflow-hidden" x-data="{
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
        <div class="relative w-full h-full">
            <template x-for="(image, index) in images" :key="index">
                <div
                    x-show="currentSlide === index"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-full"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-full"
                    class="absolute inset-0"
                >
                    <img :src="image" :alt="'Property Image ' + (index + 1)"
                            class="w-full h-full object-cover">
                </div>
            </template>
        </div>

        <template x-if="images.length > 1">
            <div>
                <button
                    @click.stop="prevSlide()"
                    x-show="canGoPrev"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 z-10 w-7 h-7 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200"
                >
                    <i class="fas fa-chevron-left text-white text-xs"></i>
                </button>

                <button
                    @click.stop="nextSlide()"
                    x-show="canGoNext"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 z-10 w-7 h-7 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200"
                >
                    <i class="fas fa-chevron-right text-white text-xs"></i>
                </button>

                <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1 z-10">
                    <template x-for="(image, index) in images" :key="index">
                        <button
                            @click.stop="goToSlide(index)"
                            class="w-1.5 h-1.5 rounded-full transition-all duration-200"
                            :class="currentSlide === index ? 'bg-white' : 'bg-white/50'"
                        ></button>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <div class="p-3 pb-0 flex flex-col justify-between h-[calc(100%-192px)]">
        <div>
            <div class="flex flex-wrap gap-1 mb-3 flex-shrink-0">
                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full uppercase">
                    {{ $property->propertyType->name ?? 'N/A' }}
                </span>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full uppercase">
                    {{ match($property->operation_type) {
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                        default => ucfirst($property->operation_type ?? 'N/A')
                    } }}
                </span>
            </div>

            <div class="text-lg font-bold text-gray-900 mb-2 flex-shrink-0">
                ${{ number_format($property->price) }} MXN
            </div>

            <div class="flex items-center gap-4 mb-3 text-gray-600 h-10 flex-shrink-0 overflow-hidden">
                @php
                    $displayFeatures = [];
                    $featureSlugs = [
                        'num_recamaras',
                        'num_banos',
                        'num_medios_banos',
                        'tamano_construccion_m2',
                        'tamano_terreno_m2',
                        'num_estacionamientos',
                        'num_niveles',
                        'anos_antiguedad',
                        'has_alberca',
                        'has_gimnasio',
                        'has_cuarto_servicio',
                        'andenes',
                        'area_maniobras',
                        'tipo_electricidad',
                        'tipo_techo',
                        'vias_comunicacion',
                        'ancho_m',
                        'alto_m',
                        'profundidad_m',
                        'tamano_bodega_m2',
                        'uso_suelo',
                    ];

                    foreach ($featureSlugs as $slug) {
                        if (count($displayFeatures) >= 3) {
                            break;
                        }

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
                    <div class="flex items-center flex-shrink-0">
                        <i class="{{ $featureData['icon'] }} mr-1.5 text-gray-500 text-sm"></i>
                        <span class="text-xs">{{ $featureData['value'] }}{{ $featureData['unit'] }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-xs flex-shrink-0">No hay características destacadas.</p>
                @endforelse
            </div>

            <p class="text-gray-900 text-xs mb-2 text-left h-8 overflow-hidden text-ellipsis flex-shrink-0">
                {{ $property->address->full_address ?? 'Ubicación no disponible' }}
            </p>
        </div>
    </div>

    <div class="p-3 pt-0 flex gap-2 flex-shrink-0">
        @if($property->contact_whatsapp_number)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->contact_whatsapp_number) }}" target="_blank"
               class="flex-1 bg-green-500 text-white py-2 px-3 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors duration-200 flex items-center justify-center"
               onclick="event.stopPropagation()"
               wire:navigate>
                <i class="fab fa-whatsapp mr-1.5 text-base"></i> WhatsApp
            </a>
        @endif
        
        <button type="button"
           class="flex-1 border border-blue-500 text-blue-500 py-2 px-3 rounded-lg font-semibold text-sm hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 flex items-center justify-center"
           onclick="event.stopPropagation()">
            <i class="fas fa-envelope mr-1.5 text-base"></i> Contactar
        </button>
    </div>
</div>