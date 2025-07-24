<div class="flex flex-wrap items-center justify-start gap-3 p-3 rounded-lg mt-32 max-w-7xl mx-auto px-8">
    <div class="relative w-full md:w-1/3 lg:w-1/4">
        <input
            type="text"
            wire:model.live.debounce.500ms="locationSearch"
            placeholder="Buscar por dirección o lugar..."
            class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            @focus="showLocationSuggestions = true"
            @click.outside="showLocationSuggestions = false"
        >
        @if($locationSearch)
            <button
                type="button"
                wire:click="clearField('locationSearch')"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif

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
                        class="px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                    >
                        {{ $suggestion }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div x-data="{ open: false, toggle() { this.open = !this.open; }, close() { this.open = false; } }" @click.outside="close()" class="relative w-full sm:w-auto">
        <button
            @click="toggle()"
            class="flex items-center justify-center h-9 px-3 py-1.5 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[10rem] w-full text-sm"
        >
            <span>{{ $operationDisplay }}</span>
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
             class="absolute z-10 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
             wire:cloak>
            <div class="py-1">
                <label class="flex items-center px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                    <input type="checkbox" wire:model.live="isForSale" class="form-checkbox h-3.5 w-3.5 text-indigo-600">
                    <span class="ml-2">Venta</span>
                </label>
                <label class="flex items-center px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                    <input type="checkbox" wire:model.live="isForRent" class="form-checkbox h-3.5 w-3.5 text-indigo-600">
                    <span class="ml-2">Renta</span>
                </label>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" @click.outside="open = false" class="relative w-full sm:w-auto">
        <button
            @click="open = !open"
            class="flex items-center justify-center h-9 px-3 py-1.5 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[10rem] w-full text-sm"
        >
            <span>{{ $propertyTypeDisplayName }}</span>
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" wire:cloak>
            <div class="py-1" role="none">
                <a href="#" wire:click.prevent="$set('selectedPropertyTypeSlug', null)" @click="open = false" class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 {{ $selectedPropertyTypeSlug === null ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    Todos los tipos
                </a>
                @foreach($propertyTypes as $type)
                    <a href="#" wire:click.prevent="$set('selectedPropertyTypeSlug', '{{ $type->slug }}')" @click="open = false" class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 {{ $selectedPropertyTypeSlug === $type->slug ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        {{ $type->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" @click.outside="open = false" class="relative w-full sm:w-auto">
        <button
            @click="open = !open"
            class="flex items-center justify-center h-9 px-3 py-1.5 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[10rem] w-full text-sm"
        >
            <span>{{ $priceDisplay }}</span>
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-10 mt-2 w-60 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none p-3" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" wire:cloak>
            <h5 class="text-sm font-semibold mb-2">Rango de Precio</h5>
           <div class="flex items-center gap-2 mb-3">
    <div class="relative w-1/2">
        <input
            type="number"
            wire:model.lazy="minPrice"
            wire:ignore.self
            placeholder="MXN $ Mínimo"
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs"
            min="0"
            x-data="{ value: @entangle('minPrice').defer }"
            x-model="value"
            @input.debounce.1000ms="$wire.set('minPrice', $event.target.value)"
        >
        @if($minPrice !== null)
            <button
                type="button"
                wire:click="clearField('minPrice')"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif
    </div>
    <span class="text-gray-500 text-xs">-</span>
    <div class="relative w-1/2">
        <input
            type="number"
            wire:model.lazy="maxPrice"
            wire:ignore.self
            placeholder="MXN $ Máximo"
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs"
            min="0"
            x-data="{ value: @entangle('maxPrice').defer }"
            x-model="value"
            @input.debounce.1000ms="$wire.set('maxPrice', $event.target.value)"
        >
        @if($maxPrice !== null)
            <button
                type="button"
                wire:click="clearField('maxPrice')"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif
    </div>
</div>
        </div>
    </div>

    <div x-data="{ open: false }" @click.outside="open = false" class="relative w-full sm:w-auto md:ml-auto">
        <button
            @click="open = !open"
            class="flex items-center justify-center h-9 px-3 py-1.5 border border-gray-300 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 min-w-[10rem] w-full text-sm"
            @if ($selectedPropertyTypeSlug && $filteredFeatures->isEmpty())
                disabled
                title="No hay filtros adicionales para este tipo de propiedad"
            @endif
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-1">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span>Filtros</span>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-10 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none p-3 max-h-80 overflow-y-auto left-0 md:left-auto md:right-0" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" wire:cloak>
            @if ($filteredFeatures->isEmpty() && $selectedPropertyTypeSlug)
                <p class="text-gray-500 text-xs text-center py-3">No hay filtros adicionales para este tipo de propiedad.</p>
            @else
                @if ($recamarasOptions->isNotEmpty())
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Recámaras</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($recamarasOptions as $option)
                                <button
                                    wire:key="recamaras-{{ $option->value }}"
                                    wire:click.prevent="setRecamaras('{{ $option->value }}')"
                                    class="px-2.5 py-1 text-xs rounded-full border {{ isset($filters['num_recamaras']) && $filters['num_recamaras'] == $option->value ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $option->label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($banosOptions->isNotEmpty())
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Baños</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($banosOptions as $option)
                                <button
                                    wire:key="banos-{{ $option->value }}"
                                    wire:click.prevent="setBanos('{{ $option->value }}')"
                                    class="px-2.5 py-1 text-xs rounded-full border {{ isset($filters['num_banos']) && $filters['num_banos'] == $option->value ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $option->label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($estacionamientosOptions->isNotEmpty())
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Estacionamiento</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($estacionamientosOptions as $option)
                                <button
                                    wire:key="estacionamiento-{{ $option->value }}"
                                    wire:click.prevent="setEstacionamientos('{{ $option->value }}')"
                                    class="px-2.5 py-1 text-xs rounded-full border {{ isset($filters['num_estacionamientos']) && $filters['num_estacionamientos'] == $option->value ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}"
                                >
                                    {{ $option->label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $hasSuperficieConstruida = $filteredFeatures->contains('slug', 'tamano_construccion_m2');
                @endphp
                @if ($hasSuperficieConstruida || !$selectedPropertyTypeSlug)
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Superficie Construida</label>
                        <div class="flex items-center gap-1.5">
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.lazy="minSuperficieConstruida"
                                    wire:ignore.self
                                    placeholder="Desde"
                                    class="w-full px-2 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                                    min="0"
                                    step="1"
                                    x-data="{ value: @entangle('minSuperficieConstruida').defer }"
                                    x-model="value"
                                    @input.debounce.1000ms="$wire.set('minSuperficieConstruida', $event.target.value)"
                                >
                                @if($minSuperficieConstruida !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('minSuperficieConstruida')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500 text-xs">m² -</span>
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.lazy="maxSuperficieConstruida"
                                    wire:ignore.self
                                    placeholder="Hasta"
                                    class="w-full px-2 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                                    min="0"
                                    step="1"
                                    x-data="{ value: @entangle('maxSuperficieConstruida').defer }"
                                    x-model="value"
                                    @input.debounce.1000ms="$wire.set('maxSuperficieConstruida', $event.target.value)"
                                >
                                @if($maxSuperficieConstruida !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('maxSuperficieConstruida')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500 text-xs">m²</span>
                        </div>
                    </div>
                @endif

                @php
                    $hasSuperficieTerreno = $filteredFeatures->contains('slug', 'tamano_terreno_m2');
                @endphp
                @if ($hasSuperficieTerreno || !$selectedPropertyTypeSlug)
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Superficie de Terreno</label>
                        <div class="flex items-center gap-1.5">
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.lazy="minSuperficieTerreno"
                                    wire:ignore.self
                                    placeholder="Desde"
                                    class="w-full px-2 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                                    min="0"
                                    step="1"
                                    x-data="{ value: @entangle('minSuperficieTerreno').defer }"
                                    x-model="value"
                                    @input.debounce.1000ms="$wire.set('minSuperficieTerreno', $event.target.value)"
                                >
                                @if($minSuperficieTerreno !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('minSuperficieTerreno')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500 text-xs">m² -</span>
                            <div class="relative w-1/2">
                                <input
                                    type="number"
                                    wire:model.lazy="maxSuperficieTerreno"
                                    wire:ignore.self
                                    placeholder="Hasta"
                                    class="w-full px-2 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                                    min="0"
                                    step="1"
                                    x-data="{ value: @entangle('maxSuperficieTerreno').defer }"
                                    x-model="value"
                                    @input.debounce.1000ms="$wire.set('maxSuperficieTerreno', $event.target.value)"
                                >
                                @if($maxSuperficieTerreno !== null)
                                    <button
                                        type="button"
                                        wire:click="clearField('maxSuperficieTerreno')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <span class="text-gray-500 text-xs">m²</span>
                        </div>
                    </div>
                @endif

                @if ($amenityFeatures->isNotEmpty() && ($recamarasOptions->isNotEmpty() || $banosOptions->isNotEmpty() || $estacionamientosOptions->isNotEmpty() || $hasSuperficieConstruida || $hasSuperficieTerreno))
                    <hr class="my-3 border-gray-200">
                @endif

                @if ($amenityFeatures->isNotEmpty())
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Amenidades</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach ($amenityFeatures as $feature)
                                <label wire:key="amenity-{{ $feature->slug }}" class="flex items-center text-xs text-gray-700">
                                    <input
                                        type="checkbox"
                                        wire:model.live="filters.{{ $feature->slug }}"
                                        class="h-3.5 w-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                    >
                                    <span class="ml-1.5">{{ $feature->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-3 text-right">
                    <button @click="open = false" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">Cerrar Filtros</button>
                </div>
            @endif
        </div>
    </div>
</div>