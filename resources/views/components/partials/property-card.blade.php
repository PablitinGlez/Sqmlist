{{-- resources/views/components/partials/property-card.blade.php --}}
@props(['property'])

<div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
    <!-- Carrusel de imágenes -->
    <div class="relative h-56 overflow-hidden" x-data="{ 
        currentSlide: 0, 
        images: {{ json_encode($property['images']) }},
        get canGoNext() { return this.currentSlide < this.images.length - 1 },
        get canGoPrev() { return this.currentSlide > 0 },
        nextSlide() { 
            if (this.canGoNext) this.currentSlide++ 
        },
        prevSlide() { 
            if (this.canGoPrev) this.currentSlide-- 
        },
        goToSlide(index) { 
            this.currentSlide = index 
        }
    }">
        <!-- Botón de favoritos -->
        <button class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200">
            <i class="far fa-heart text-white text-lg hover:text-red-400 transition-colors"></i>
        </button>

        <!-- Imágenes del carrusel -->
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
                    <img :src="image" :alt="'{{ $property['title'] }} - Imagen ' + (index + 1)" 
                         class="w-full h-full object-cover">
                </div>
            </template>
        </div>

        <!-- Controles de navegación -->
        <template x-if="images.length > 1">
            <div>
                <!-- Botón anterior -->
                <button 
                    x-show="canGoPrev"
                    @click="prevSlide()"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200"
                >
                    <i class="fas fa-chevron-left text-white text-sm"></i>
                </button>

                <!-- Botón siguiente -->
                <button 
                    x-show="canGoNext"
                    @click="nextSlide()"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200"
                >
                    <i class="fas fa-chevron-right text-white text-sm"></i>
                </button>

                <!-- Indicadores (puntitos) -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                    <template x-for="(image, index) in images" :key="index">
                        <button 
                            @click="goToSlide(index)"
                            class="w-2 h-2 rounded-full transition-all duration-200"
                            :class="currentSlide === index ? 'bg-white' : 'bg-white/50'"
                        ></button>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- Contenido de la card -->
    <div class="p-4">
        <!-- Badges/Etiquetas -->
        <div class="flex flex-wrap gap-2 mb-4">
            @if($property['featured'])
                <span class="px-3 py-1 bg-yellow-400 text-yellow-900 text-xs font-semibold rounded-full">
                    DESTACADO
                </span>
            @endif
            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full uppercase">
                {{ $property['type'] }}
            </span>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full uppercase">
                {{ $property['operation'] }}
            </span>
        </div>

        <!-- Título de la propiedad -->
        <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-2">
            {{ $property['title'] }}
        </h3>

        <!-- Ubicación -->
        <p class="text-gray-600 mb-3 flex items-center">
            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
            {{ $property['location'] }}
        </p>

        <!-- Precio -->
        <div class="text-2xl font-bold text-gray-900 mb-3">
            ${{ number_format($property['price']) }} 
            <span class="text-lg text-gray-600">{{ $property['currency'] }}</span>
        </div>

        <!-- Estadísticas -->
        <div class="flex items-center gap-6 mb-6 text-gray-600">
            @if($property['bedrooms'])
                <div class="flex items-center">
                    <i class="fas fa-bed mr-2"></i>
                    <span class="text-sm">{{ $property['bedrooms'] }}</span>
                </div>
            @endif
            
            @if($property['bathrooms'])
                <div class="flex items-center">
                    <i class="fas fa-bath mr-2"></i>
                    <span class="text-sm">{{ $property['bathrooms'] }}</span>
                </div>
            @endif
            
            @if($property['area'])
                <div class="flex items-center">
                    <i class="fas fa-ruler-combined mr-2"></i>
                    <span class="text-sm">{{ $property['area'] }} m²</span>
                </div>
            @endif
        </div>

        <!-- Botones de acción -->
        <div class="flex gap-3">
            <button class="flex-1 bg-blue-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-envelope mr-2"></i>
                Contactar
            </button>
            <button class="flex-1 bg-green-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-600 transition-colors duration-200 flex items-center justify-center">
                <i class="fab fa-whatsapp mr-2"></i>
                WhatsApp
            </button>
        </div>
    </div>
</div>