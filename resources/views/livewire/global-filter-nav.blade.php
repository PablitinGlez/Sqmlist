<div class="flex flex-wrap items-center justify-start gap-4 p-4 rounded-lg mt-32 max-w-7xl mx-auto px-8">

    {{-- 1. Barra de Búsqueda por Ubicación (Ancho ajustado) --}}
    <div class="relative w-full md:w-1/3 lg:w-1/4">
        <input
            type="text"
            wire:model.live.debounce.500ms="locationSearch"
            placeholder="Buscar por dirección o lugar..."
            class="w-full h-10 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            @focus="showLocationSuggestions = true"
            @click.outside="showLocationSuggestions = false"
        >
        {{-- Botón para limpiar el campo de búsqueda de ubicación --}}
        @if($locationSearch)
            <button
                type="button"
                wire:click="clearField('locationSearch')"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif

        {{-- Sugerencias de Ubicación --}}
        <div
            x-show="showLocationSuggestions && locationSuggestions.length > 0"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-20 mt-1 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto"
            wire:cloak
        >
            <ul class="py-1">
                @foreach($locationSuggestions as $suggestion)
                    <li
                        wire:click="selectLocationSuggestion('{{ $suggestion }}')"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                    >
                        {{ $suggestion }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- 2. Dropdown: Tipo de Operación (Título centrado, ancho consistente) --}}
    <div x-data="{
        open: false,
        toggle() { this.open = !this.open; },
        close() { this.open = false; }
    }" @click.outside="close()" class="relative w-full sm:w-auto">
        <button
            @click="toggle()"
            class="flex items-center justify-center h-10 px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[12rem] w-full"
        >
            <span class="">{{ $operationDisplay }}</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
             wire:cloak>
            <div class="py-1">
                <label class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <input type="checkbox" wire:model.live="isForSale" class="form-checkbox h-4 w-4 text-indigo-600">
                    <span class="ml-2">Venta</span>
                </label>
                <label class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <input type="checkbox" wire:model.live="isForRent" class="form-checkbox h-4 w-4 text-indigo-600">
                    <span class="ml-2">Renta</span>
                </label>
            </div>
        </div>
    </div>

    {{-- 3. Dropdown: Tipo de Propiedad (Título centrado, ancho consistente) --}}
    <div x-data="{ open: false }" @click.outside="open = false" class="relative w-full sm:w-auto">
        <button
            @click="open = !open"
            class="flex items-center justify-center h-10 px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[12rem] w-full"
        >
            <span class="">{{ $propertyTypeDisplayName }}</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" wire:cloak>
            <div class="py-1" role="none">
                {{-- Opción "Todos los tipos" --}}
                <a href="#" wire:click.prevent="$set('selectedPropertyTypeSlug', null)" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $selectedPropertyTypeSlug === null ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    Todos los tipos
                </a>
                @foreach($propertyTypes as $type)
                    {{-- Eliminado wire:navigate de aquí --}}
                    <a href="#" wire:click.prevent="$set('selectedPropertyTypeSlug', '{{ $type->slug }}')" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $selectedPropertyTypeSlug === $type->slug ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        {{ $type->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 4. Dropdown: Precio (Título centrado, ancho consistente) --}}
    <div x-data="{ open: false }" @click.outside="open = false" class="relative w-full sm:w-auto">
        <button
            @click="open = !open"
            class="flex items-center justify-center h-10 px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[12rem] w-full"
        >
            <span class="">{{ $priceDisplay }}</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-10 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none p-4" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" wire:cloak>
            <h5 class="text-md font-semibold mb-3">Rango de Precio</h5>
            <div class="flex items-center gap-2 mb-3">
                <input
                    type="number"
                    wire:model.live.debounce.500ms="minPrice"
                    placeholder="MXN $ Mínimo"
                    class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                >
                <span class="text-gray-500">-</span>
                <input
                    type="number"
                    wire:model.live.debounce.500ms="maxPrice"
                    placeholder="MXN $ Máximo"
                    class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                >
            </div>
        </div>
    </div>

    {{-- 5. Dropdown: Filtros Adicionales (En nueva fila en responsive, alineado a la derecha) --}}
    <div x-data="{ open: false }" @click.outside="open = false" class="relative w-full sm:w-auto md:ml-auto">
        {{-- Botón principal de Filtros (Icono añadido y título centrado) --}}
        <button
            @click="open = !open"
            class="flex items-center justify-center h-10 px-4 py-2 border border-gray-300 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[12rem] w-full"
            {{-- Deshabilitar el botón si se selecciona un tipo de propiedad que no tiene filtros relevantes --}}
            @if ($selectedPropertyTypeSlug && $filteredFeatures->isEmpty())
                disabled
                title="No hay filtros adicionales para este tipo de propiedad"
            @endif
        >
            {{-- Icono de filtro --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-1">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span class="">Filtros</span>

        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-10 mt-2 w-96 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none p-4 max-h-80 overflow-y-auto left-0 md:left-auto md:right-0" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" wire:cloak>
            {{-- Mensaje si no hay filtros disponibles para el tipo de propiedad --}}
            @if ($filteredFeatures->isEmpty() && $selectedPropertyTypeSlug)
                <p class="text-gray-500 text-sm text-center py-4">No hay filtros adicionales para este tipo de propiedad.</p>
            @else
                {{-- Recámaras --}}
                @if ($recamarasOptions->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recámaras</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($recamarasOptions as $option)
                                <button
                                    wire:key="recamaras-{{ $option->value }}"
                                    wire:click.prevent="setRecamaras('{{ $option->value }}')"
                                    class="px-3 py-1 text-sm rounded-full border
                                    {{ isset($filters['num_recamaras']) && $filters['num_recamaras'] == $option->value ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $option->label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Baños --}}
                @if ($banosOptions->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Baños</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($banosOptions as $option)
                                <button
                                    wire:key="banos-{{ $option->value }}"
                                    wire:click.prevent="setBanos('{{ $option->value }}')"
                                    class="px-3 py-1 text-sm rounded-full border
                                    {{ isset($filters['num_banos']) && $filters['num_banos'] == $option->value ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $option->label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Estacionamiento --}}
                @if ($estacionamientosOptions->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Estacionamiento</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($estacionamientosOptions as $option)
                                <button
                                    wire:key="estacionamiento-{{ $option->value }}"
                                    wire:click.prevent="setEstacionamientos('{{ $option->value }}')"
                                    class="px-3 py-1 text-sm rounded-full border
                                    {{ isset($filters['num_estacionamientos']) && $filters['num_estacionamientos'] == $option->value ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $option->label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Superficie Construida --}}
                @php
                    $hasSuperficieConstruida = $filteredFeatures->contains('slug', 'tamano_construccion_m2');
                @endphp
                @if ($hasSuperficieConstruida || !$selectedPropertyTypeSlug)
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Superficie Construida</label>
                        <div class="flex items-center gap-2">
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.live.debounce.800ms="minSuperficieConstruida"
                                    placeholder="Desde"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    min="0"
                                    step="1"
                                >
                                @if($minSuperficieConstruida !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('minSuperficieConstruida')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500">m² -</span>
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.live.debounce.800ms="maxSuperficieConstruida"
                                    placeholder="Hasta"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    min="0"
                                    step="1"
                                >
                                @if($maxSuperficieConstruida !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('maxSuperficieConstruida')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500">m²</span>
                        </div>
                    </div>
                @endif

                {{-- Superficie de Terreno --}}
                @php
                    $hasSuperficieTerreno = $filteredFeatures->contains('slug', 'tamano_terreno_m2');
                @endphp
                @if ($hasSuperficieTerreno || !$selectedPropertyTypeSlug)
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Superficie de Terreno</label>
                        <div class="flex items-center gap-2">
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.live.debounce.800ms="minSuperficieTerreno"
                                    placeholder="Desde"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    min="0"
                                    step="1"
                                >
                                @if($minSuperficieTerreno !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('minSuperficieTerreno')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500">m² -</span>
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.live.debounce.800ms="maxSuperficieTerreno"
                                    placeholder="Hasta"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    min="0"
                                    step="1"
                                >
                                @if($maxSuperficieTerreno !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('maxSuperficieTerreno')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500">m²</span>
                        </div>
                    </div>
                @endif

                {{-- Línea divisoria --}}
                @if ($amenityFeatures->isNotEmpty() && ($recamarasOptions->isNotEmpty() || $banosOptions->isNotEmpty() || $estacionamientosOptions->isNotEmpty() || $hasSuperficieConstruida || $hasSuperficieTerreno))
                    <hr class="my-4 border-gray-200">
                @endif

                {{-- Amenidades --}}
                @if ($amenityFeatures->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amenidades</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($amenityFeatures as $feature)
                                <label wire:key="amenity-{{ $feature->slug }}" class="flex items-center text-sm text-gray-700">
                                    <input
                                        type="checkbox"
                                        wire:model.live="filters.{{ $feature->slug }}"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                    >
                                    <span class="ml-2">{{ $feature->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Botón para cerrar el dropdown --}}
                <div class="mt-4 text-right">
                    <button @click="open = false" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Cerrar Filtros</button>
                </div>
            @endif
        </div>
    </div>

    {{-- Sección de Depuración: Filtros Aplicados --}}
    <div class="w-full mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
        <h4 class="font-semibold mb-2">Filtros Actuales </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
            <div><strong>Ubicación:</strong> {{ $locationSearch ?: 'Ninguna' }}</div>
            <div><strong>Operación:</strong> {{ $operationDisplay }} (Venta: {{ $isForSale ? 'Sí' : 'No' }}, Renta: {{ $isForRent ? 'Sí' : 'No' }})</div>
            <div><strong>Tipo Propiedad:</strong> {{ $propertyTypeDisplayName }} (Slug: {{ $selectedPropertyTypeSlug ?: 'N/A' }})</div>
            <div><strong>Precio:</strong> {{ $priceDisplay }} (Min: {{ $minPrice ?: 'N/A' }}, Max: {{ $maxPrice ?: 'N/A' }})</div>

            <div class="col-span-full mt-2">
                <strong>Filtros Adicionales:</strong>
                <ul class="list-disc list-inside ml-4">
                    @php
                        $significantFilters = [];
                        foreach ($filters as $key => $value) {
                            if (is_bool($value)) {
                                $significantFilters[] = "$key: " . ($value ? 'Sí' : 'No');
                            } elseif (!is_null($value) && $value !== '' && $value !== 'Todos') {
                                $significantFilters[] = "$key: $value";
                            }
                        }
                        if ($minSuperficieConstruida !== null) { // Solo mostrar si no es null
                            $significantFilters[] = "Superficie Construida Mínima: " . $minSuperficieConstruida . " m²";
                        }
                        if ($maxSuperficieConstruida !== null) { // Solo mostrar si no es null
                            $significantFilters[] = "Superficie Construida Máxima: " . $maxSuperficieConstruida . " m²";
                        }
                        if ($minSuperficieTerreno !== null) { // Solo mostrar si no es null
                            $significantFilters[] = "Superficie Terreno Mínima: " . $minSuperficieTerreno . " m²";
                        }
                        if ($maxSuperficieTerreno !== null) { // Solo mostrar si no es null
                            $significantFilters[] = "Superficie Terreno Máxima: " . $maxSuperficieTerreno . " m²";
                        }
                    @endphp

                    @forelse ($significantFilters as $filterText)
                        <li>{{ $filterText }}</li>
                    @empty
                        <p class="ml-4">Ningún filtro adicional aplicado.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
