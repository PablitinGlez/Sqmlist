<div
    x-data="{
        // No necesitamos pasar googleMapsApiKey a Alpine.js aquí,
        // ya que la lógica de la API de Google Maps se maneja en el backend de Livewire.
    }"
    class="space-y-6 p-4 bg-white rounded-lg shadow-md"
>
    <!-- Input principal de búsqueda con autocompletado -->
    <div class="relative">
        <label for="searchAddress" class="block text-sm font-medium text-gray-700 mb-2">
            Dirección y ubicación
        </label>
        <input
            type="text"
            id="searchAddress"
            wire:model.live.debounce.300ms="searchAddress"
            placeholder="Busca por calle, colonia o municipio"
            class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
            autocomplete="off"
            @focus="$wire.showSuggestions = true" {{-- Mostrar sugerencias al enfocar, usando $wire --}}
            @keydown.escape="$wire.showSuggestions = false" {{-- Ocultar sugerencias al presionar Escape, usando $wire --}}
        >

        <!-- Sugerencias de autocompletado -->
        {{-- Accedemos a showSuggestions y suggestions directamente ya que son propiedades públicas de Livewire --}}
        @if($showSuggestions && count($suggestions) > 0)
            <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                @foreach($suggestions as $suggestion)
                    <div
                        wire:click="selectSuggestion('{{ $suggestion['place_id'] }}', '{{ $suggestion['description'] }}')"
                        class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                    >
                        <div class="font-medium text-gray-900">{{ $suggestion['main_text'] }}</div>
                        @if($suggestion['secondary_text'])
                            <div class="text-sm text-gray-500">{{ $suggestion['secondary_text'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Overlay para cerrar sugerencias cuando se hace clic fuera -->
        @if($showSuggestions)
            <div
                wire:click="hideSuggestions"
                class="fixed inset-0 z-40"
            ></div>
        @endif
    </div>

    <!-- Mostrar dirección seleccionada si existe -->
    @if($showDetailedFields)
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Propiedad · Dirección y Ubicación
            </h3>
            <div class="text-sm text-gray-600">
                <strong>Dirección seleccionada:</strong> {{ $searchAddress }}
            </div>
        </div>
    @endif

    <!-- Campos detallados de dirección -->
    @if($showDetailedFields)
        <div class="space-y-6">
            <!-- Calle y números -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="street" class="block text-sm font-medium text-gray-700 mb-1">
                        Calle<span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="street"
                        wire:model.live="street"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Nombre de la calle"
                    >
                </div>

                <div>
                    <label for="outdoor_number" class="block text-sm font-medium text-gray-700 mb-1">
                        Núm. exterior (opcional)
                    </label>
                    <input
                        type="text"
                        id="outdoor_number"
                        wire:model.live="outdoor_number"
                        x-bind:disabled="$wire.no_external_number" {{-- Usar $wire para acceder a propiedades de Livewire --}}
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-gray-100"
                        placeholder="123"
                    >
                    <div class="mt-2 flex items-center">
                        <input
                            type="checkbox"
                            id="no_external_number"
                            wire:model.live="no_external_number"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="no_external_number" class="ml-2 block text-sm text-gray-900">
                            Sin número (s/n)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Número interior -->
            <div>
                <label for="interior_number" class="block text-sm font-medium text-gray-700 mb-1">
                    Núm. interior (opcional)
                </label>
                <input
                    type="text"
                    id="interior_number"
                    wire:model.live="interior_number"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Apto 4B, Depto 2, etc."
                >
            </div>

            <!-- Información geográfica -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="state_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <input
                        type="text"
                        id="state_name"
                        wire:model.live="state_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50"
                        placeholder="Estado"
                        readonly
                    >
                </div>

                <div>
                    <label for="municipality_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Municipio o alcaldía
                    </label>
                    <input
                        type="text"
                        id="municipality_name"
                        wire:model.live="municipality_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Municipio"
                    >
                </div>

                <div>
                    <label for="neighborhood_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Colonia
                    </label>
                    <input
                        type="text"
                        id="neighborhood_name"
                        wire:model.live="neighborhood_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Colonia"
                    >
                </div>
            </div>

            <!-- Código postal -->
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                    Código postal
                </label>
                <input
                    type="text"
                    id="postal_code"
                    wire:model.live="postal_code"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="12345"
                    maxlength="5"
                >
            </div>

            <!-- Información de ubicación -->
            @if($latitude && $longitude)
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-medium text-green-800 mb-2">Ubicación encontrada</h4>
                    <p class="text-sm text-green-700">
                        <strong>Coordenadas:</strong> {{ number_format($latitude, 6) }}, {{ number_format($longitude, 6) }}
                    </p>
                    <p class="text-sm text-green-600 mt-1">
                        Arriba se muestra la dirección detectada. Si es incorrecta, puedes ajustarla manualmente.
                    </p>
                </div>
            @endif

            <!-- Información del nivel de dirección -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">Información detectada</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2 {{ $addressLevel === 'street' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        <span class="{{ $addressLevel === 'street' ? 'text-green-700 font-medium' : 'text-gray-500' }}">
                            Calle
                        </span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2 {{ in_array($addressLevel, ['street', 'neighborhood']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        <span class="{{ in_array($addressLevel, ['street', 'neighborhood']) ? 'text-green-700 font-medium' : 'text-gray-500' }}">
                            Colonia
                        </span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2 {{ in_array($addressLevel, ['street', 'neighborhood', 'municipality']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        <span class="{{ in_array($addressLevel, ['street', 'neighborhood', 'municipality']) ? 'text-green-700 font-medium' : 'text-gray-500' }}">
                            Municipio
                        </span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2 {{ $addressLevel !== 'none' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        <span class="{{ $addressLevel !== 'none' ? 'text-green-700 font-medium' : 'text-gray-500' }}">
                            Estado
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mensaje de ayuda -->
    @if(!$showDetailedFields)
        <div class="text-center py-8">
            <div class="text-gray-400 mb-2">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <p class="text-gray-500 text-sm">
                Comienza escribiendo el nombre de un estado, municipio, colonia o calle para encontrar tu dirección
            </p>
        </div>
    @endif

    <!-- Botón de continuar (solo visual, la navegación la maneja Filament) -->
    @if($showDetailedFields)
        <div class="flex justify-end pt-4 border-t">
            <button
                type="button"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                {{-- No se necesita onclick="alert(...)", la navegación del Wizard la maneja Filament --}}
            >
                Continuar
            </button>
        </div>
    @endif

    <!-- Campos ocultos para coordenadas -->
    <input type="hidden" wire:model="latitude">
    <input type="hidden" wire:model="longitude">
    <input type="hidden" wire:model="google_place_id">
</div>
