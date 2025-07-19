<div class="space-y-4">
    <div class="">
        <div class="fi-section-header mb-4">
            <h2 class="fi-section-header-heading text-lg font-semibold text-gray-950 dark:text-white">
                Dirección y ubicación
            </h2>
        </div>

        <div class="fi-input-wrapper flex items-center overflow-hidden rounded-md shadow-sm ring-1 transition duration-75 bg-white dark:bg-gray-900 ring-gray-950/5 dark:ring-white/10 mb-3">
            <label for="address-search" class="sr-only">Busca por calle, colonia o municipio</label>
            <div class="flex-1">
                <input
                    id="address-search"
                    type="text"
                    placeholder="Busca por calle, colonia o municipio"
                    wire:model.live.debounce.500ms="search"
                    class="fi-input block w-full border-none bg-transparent py-2 pe-2 ps-2 text-sm text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:opacity-70 dark:text-white dark:placeholder:text-gray-500 sm:text-xs sm:leading-5"
                >
            </div>
            <div wire:loading wire:target="search" class="pe-2">
                <svg class="fi-loading-indicator h-4 w-4 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.928M2.986 9.348H8.01M12 20.782v-5.542m0-1.922v-5.542"></path>
                </svg>
            </div>
            @if($search)
                <button 
                    wire:click="clearForm" 
                    class="pe-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                    title="Limpiar búsqueda"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>

        @if (!empty($suggestions) && strlen($search) >= 3)
            <div class="fi-input-wrapper overflow-hidden rounded-md shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 bg-white dark:bg-gray-900 mb-4">
                <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Selecciona la dirección de tu propiedad
                    </p>
                </div>
                <ul class="max-h-48 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($suggestions as $suggestion)
                        <li
                            wire:click="selectSuggestion('{{ $suggestion['place_id'] }}', '{{ $suggestion['description'] }}')"
                            class="cursor-pointer px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs transition-colors duration-150"
                        >
                            <div class="flex items-start">
                                <svg class="w-3 h-3 mt-0.5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="break-words">{{ $suggestion['description'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($selectedAddressData['state_name'] || $show_municipality_select || $show_colonia_select || ($selectedAddressData['street'] && !$search))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="fi-input-wrapper flex flex-col md:col-span-2">
                    <label for="address-street" class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                        Calle <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="address-street"
                        type="text"
                        wire:model.live="selectedAddressData.street"
                        required
                        class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                    />
                </div>

                <div class="fi-input-wrapper flex flex-col">
                    <label for="address-outdoor-number" class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                        Núm. exterior (opcional)
                    </label>
                    <input
                        id="address-outdoor-number"
                        type="text"
                        wire:model.live="selectedAddressData.outdoor_number"
                        x-bind:disabled="$wire.is_outdoor_number_sn"
                        class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                    />
                    <label for="outdoor-sn-checkbox" class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <input type="checkbox" id="outdoor-sn-checkbox" wire:model.live="is_outdoor_number_sn" class="mr-1 rounded text-primary-600 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-primary-500 dark:checked:border-primary-500 h-3 w-3">
                        Sin número (s/n)
                    </label>
                </div>

                <div class="fi-input-wrapper flex flex-col">
                    <label for="address-interior-number" class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                        Núm. interior (opcional)
                    </label>
                    <input
                        id="address-interior-number"
                        type="text"
                        wire:model.live="selectedAddressData.interior_number"
                        x-bind:disabled="$wire.is_interior_number_sn"
                        class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                    />
                    <label for="interior-sn-checkbox" class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <input type="checkbox" id="interior-sn-checkbox" wire:model.live="is_interior_number_sn" class="mr-1 rounded text-primary-600 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-primary-500 dark:checked:border-primary-500 h-3 w-3">
                        Sin número (s/n)
                    </label>
                </div>

                <div class="fi-input-wrapper flex flex-col">
                    <label for="state-select" class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                        Estado
                    </label>
                    <select
                        id="state-select"
                        wire:model.live="selected_state_id"
                        class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                    >
                        <option value="">Selecciona un estado</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" @if($selected_state_id == $state->id) selected @endif>
                                {{ $state->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($show_municipality_select)
                    <div class="fi-input-wrapper flex flex-col">
                        <label for="municipality-select" class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                            Municipio o alcaldía
                        </label>
                        <select
                            id="municipality-select"
                            wire:model.live="selected_municipality_id"
                            class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                        >
                            <option value="">Escribe o selecciona</option>
                            @foreach($municipalities as $municipality)
                                <option value="{{ $municipality->id }}" @if($selected_municipality_id == $municipality->id) selected @endif>
                                    {{ $municipality->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($show_colonia_select)
                    <div class="fi-input-wrapper flex flex-col">
                        <label for="colonia-select" class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                            Colonia
                        </label>
                        <select
                            id="colonia-select"
                            wire:model.live="selected_colonia_id"
                            class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                        >
                            <option value="">Selecciona una colonia</option>
                            @foreach($colonias as $colonia)
                                <option value="{{ $colonia->id }}" @if($selected_colonia_id == $colonia->id) selected @endif>
                                    {{ $colonia->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($selectedAddressData['postal_code'])
                    <div class="fi-input-wrapper flex flex-col">
                        <label class="fi-input-label mb-1 text-xs font-medium text-gray-700 dark:text-gray-200">
                            Código postal
                        </label>
                        <input
                            type="text"
                            wire:model="selectedAddressData.postal_code"
                            disabled
                            class="fi-input block w-full rounded-md border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-xs sm:leading-5 px-2 py-1.5"
                        />
                    </div>
                @endif
            </div>

            <input type="hidden" wire:model="selectedAddressData.latitude">
            <input type="hidden" wire:model="selectedAddressData.longitude">
            <input type="hidden" wire:model="selectedAddressData.google_place_id">
            <input type="hidden" wire:model="selectedAddressData.google_address_components">
            <input type="hidden" wire:model="selectedAddressData.no_external_number">
            <input type="hidden" wire:model="selectedAddressData.no_interior_number">
        @endif

        @if($show_map)
            <div class="mt-6">
                <div class="fi-section-header mb-3">
                    <h3 class="fi-section-header-heading text-lg font-semibold text-gray-950 dark:text-white">
                        Ubicación exacta
                    </h3>
                    <p class="fi-section-header-description text-xs text-gray-600 dark:text-gray-400 mt-1">
                        Arrastra el marcador a la ubicación exacta de tu propiedad
                    </p>
                </div>

                <div class="mb-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-md">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="text-xs text-gray-700 dark:text-gray-300">
                            <strong>Dirección:</strong>
                            {{ $selectedAddressData['street'] }}
                            @if($selectedAddressData['outdoor_number'] && $selectedAddressData['outdoor_number'] !== 'S/N')
                                {{ $selectedAddressData['outdoor_number'] }}
                            @elseif($selectedAddressData['outdoor_number'] === 'S/N')
                                S/N
                            @endif
                            @if($selectedAddressData['interior_number'] && $selectedAddressData['interior_number'] !== 'S/N')
                                Int. {{ $selectedAddressData['interior_number'] }}
                            @elseif($selectedAddressData['interior_number'] === 'S/N')
                                Int. S/N
                            @endif
                            <br>
                            {{ $selectedAddressData['neighborhood_name'] }}, {{ $selectedAddressData['municipality_name'] }}, {{ $selectedAddressData['state_name'] }}, C.P. {{ $selectedAddressData['postal_code'] }}
                        </div>
                    </div>
                </div>

                <div id="map" class="w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-md" wire:ignore></div>
            </div>
        @endif
    </div>

    <div wire:loading.flex wire:target="selectSuggestion,updatedSelectedStateId,updatedSelectedMunicipalityId,updatedSelectedColoniaId" class="items-center justify-center p-3">
        <svg class="animate-spin h-5 w-5 text-primary-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-xs text-gray-600 dark:text-gray-400">Cargando...</span>
    </div>
</div>

@push('scripts')
<script>
    let map;
    let marker;
    let initialLat = @json($selectedAddressData['latitude'] ?? 19.4326);
    let initialLng = @json($selectedAddressData['longitude'] ?? -99.1332);
    let showMapInitial = @json($show_map);

    window.initMap = function () {
        document.addEventListener('livewire:initialized', () => {
            Livewire.find('{{ $this->getId() }}').dispatch('setInitialAddressData', {
                initialData: @json($selectedAddressData)
            });

            if (showMapInitial && coordenadasValidas(initialLat, initialLng)) {
                setTimeout(() => {
                    crearOActualizarMapa(initialLat, initialLng);
                }, 300);
            }

            Livewire.on('updateMap', (eventData) => {
                if (!validarDatosEvento(eventData)) {
                    return;
                }

                const data = eventData[0];
                const newLat = parseFloat(data.lat);
                const newLng = parseFloat(data.lng);

                if (!coordenadasValidas(newLat, newLng)) {
                    return;
                }
                
                setTimeout(() => {
                    crearOActualizarMapa(newLat, newLng);
                }, 200);
            });

            Livewire.on('resetMap', () => {
                limpiarMapa();
            });
        });
    }

    function coordenadasValidas(lat, lng) {
        return lat !== null && lng !== null && !isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0;
    }

    function validarDatosEvento(eventData) {
        return Array.isArray(eventData) && 
               eventData.length > 0 && 
               eventData[0] && 
               typeof eventData[0].lat !== 'undefined' && 
               typeof eventData[0].lng !== 'undefined';
    }

    function crearOActualizarMapa(lat, lng) {
        const mapElement = document.getElementById('map');
        if (!mapElement) {
            return;
        }

        verificarDimensiones(mapElement);

        const centro = { lat: lat, lng: lng };

        if (map && marker) {
            try {
                marker.setPosition(centro);
                map.setCenter(centro);
                map.setZoom(16);
                
                google.maps.event.trigger(map, 'resize');
                map.setCenter(centro);
            } catch (error) {
                limpiarMapa();
                crearNuevoMapa(lat, lng, mapElement);
            }
        } else {
            crearNuevoMapa(lat, lng, mapElement);
        }
    }

    function crearNuevoMapa(lat, lng, mapElement) {
        try {
            mapElement.innerHTML = ''; 
            
            const centro = { lat: lat, lng: lng };
            
            map = new google.maps.Map(mapElement, {
                center: centro,
                zoom: 16,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                zoomControl: true,
                gestureHandling: 'cooperative',
                styles: [],
                disableDefaultUI: false,
                clickableIcons: false,
                backgroundColor: '#e5e3df'
            });
            
            marker = new google.maps.Marker({
                position: centro,
                map: map,
                draggable: true,
                title: 'Arrastra para ajustar la ubicación exacta',
                animation: google.maps.Animation.DROP,
                icon: {
                    url: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="%231E90FF" class="icon icon-tabler icons-tabler-filled icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" /></svg>',
                    scaledSize: new google.maps.Size(24, 24)
                }
            });
            
            marker.addListener('dragend', function() {
                const nuevaLat = marker.getPosition().lat();
                const nuevaLng = marker.getPosition().lng();
                
                Livewire.dispatch('mapLocationUpdated', { 
                    lat: nuevaLat, 
                    lng: nuevaLng 
                });
            });

            google.maps.event.addListenerOnce(map, 'idle', function() {
                google.maps.event.trigger(map, 'resize');
                map.setCenter(centro);
            });
            
        } catch (error) {
            
        }
    }

    function verificarDimensiones(mapElement) {
        mapElement.style.width = '100%';
        mapElement.style.height = '256px';
        mapElement.style.minHeight = '192px';
        mapElement.style.display = 'block';
        mapElement.style.position = 'relative';
        mapElement.style.backgroundColor = '#e5e3df';
        
        mapElement.offsetHeight; 
    }

    function limpiarMapa() {
        if (marker) {
            marker.setMap(null);
            marker = null;
        }
        
        if (map) {
            map = null;
        }
        
        const mapElement = document.getElementById('map');
        if (mapElement) {
            mapElement.innerHTML = ''; 
        }
    }
</script>


@endpush
