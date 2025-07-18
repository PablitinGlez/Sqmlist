{{-- Formulario de búsqueda en el Hero con cambios INSTANTÁNEOS --}}
<div class="max-w-2xl mx-auto">
    <!-- Opciones En Venta / En Renta - MEJORADO para cambio instantáneo -->
    <div class="flex justify-start mb-8" x-data="{
        localOperationType: '{{ $operationType }}', // Inicializar con el valor del servidor

        // Función para cambio INSTANTÁNEO del indicador
        changeOperationType(type) {
            // Cambio inmediato en el DOM (sin esperar a Livewire)
            this.localOperationType = type;

            // Actualizar Livewire en segundo plano (sin bloquear la UI)
            this.$nextTick(() => {
                $wire.setOperationType(type);
            });
        }
    }">
        <div class="flex gap-8">
            {{-- Botón En Venta --}}
            <button type="button"
                    @click="changeOperationType('sale')"
                    class="px-4 py-2 text-white font-medium transition-all duration-100 relative hover:opacity-80"
                    :class="{ 'border-b-2 border-blue-500': localOperationType === 'sale' }">
                En Venta
            </button>

            {{-- Botón En Renta --}}
            <button type="button"
                    @click="changeOperationType('rent')"
                    class="px-4 py-2 text-white font-medium transition-all duration-100 relative hover:opacity-80"
                    :class="{ 'border-b-2 border-blue-500': localOperationType === 'rent' }">
                En Renta
            </button>
        </div>
    </div>

    <!-- Línea divisoria -->
    <div class="max-w-2xl mx-auto h-px bg-white/30 mb-8"></div>

    <form wire:submit.prevent="searchProperties" class="flex flex-col md:flex-row gap-4">
        <!-- Dropdown de Tipo de Propiedad -->
        <div class="relative">
            <select wire:model.live="selectedPropertyType"
                    class="w-full md:w-48 px-4 py-3 bg-white/90 backdrop-blur-sm rounded-lg border border-white/20 text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                {{-- CAMBIO CLAVE AQUÍ: REMOVIDO el atributo 'disabled' --}}
                <option value="">Tipo de Propiedad</option>
                @foreach($propertyTypes as $type)
                    <option value="{{ $type->slug }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input de búsqueda con sugerencias -->
        <div class="flex-1 relative" x-data="{
            isFocused: false,
            init() {
                // Asegura que el dropdown se oculte al hacer clic fuera
                this.$watch('$wire.locationSuggestions', () => {
                    if (this.$wire.locationSuggestions.length > 0) {
                        this.showSuggestions = true;
                    } else {
                        this.showSuggestions = false;
                    }
                });
            }
        }" @click.outside="isFocused = false; $wire.showSuggestions = false">
            <input type="text"
                   wire:model.live.debounce.500ms="locationSearch"
                   placeholder="Buscar por ubicación..."
                   class="w-full px-4 py-3 pr-12 bg-white/90 backdrop-blur-sm rounded-lg border border-white/20 text-gray-800 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                   @focus="isFocused = true; $wire.showSuggestions = $wire.locationSuggestions.length > 0"
                   @keydown.escape="isFocused = false; $wire.showSuggestions = false"
            >
            {{-- Loader para las sugerencias de ubicación (junto al input) --}}
            {{-- Este loader se muestra mientras se escribe y Livewire busca sugerencias --}}
            <div wire:loading wire:target="locationSearch" class="absolute right-12 top-1/2 transform -translate-y-1/2">
                <div class="animate-spin rounded-full h-4 w-4 border-t-2 border-blue-400"></div>
            </div>

            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="searchProperties"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600 hover:text-blue-700 transition-colors">
                {{-- Icono de lupa (visible cuando no está cargando la búsqueda principal) --}}
                <i class="fas fa-search text-lg" wire:loading.remove wire:target="searchProperties"></i>
                {{-- Loader giratorio (visible cuando está cargando la búsqueda principal) --}}
                <div wire:loading wire:target="searchProperties" class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
            </button>

            {{-- Contenedor de Sugerencias/Sin Resultados --}}
            <div
                x-show="isFocused && $wire.locationSearch.length >= 2"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute z-20 mt-1 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto text-left"
                wire:cloak
            >
                @if(count($locationSuggestions) > 0)
                    <ul class="py-1">
                        @foreach($locationSuggestions as $suggestion)
                            <li
                                wire:click="selectSuggestion('{{ $suggestion }}')"
                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                            >
                                {{ $suggestion }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{-- Mensaje de "Sin resultados" --}}
                    @if(strlen($locationSearch) >= 2)
                        <p class="px-4 py-2 text-sm text-gray-500">No se encontraron resultados.</p>
                    @endif
                @endif
            </div>
        </div>
    </form>
</div>
