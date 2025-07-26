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

                <!-- Indicador de carga del mapa -->
                <div id="map-loading" class="w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center">
                    <div class="text-center">
                        <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Cargando mapa...</p>
                    </div>
                </div>

                <!-- El mapa real -->
                <div id="map" class="w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-md hidden" wire:ignore></div>
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
    // Variables globales para el mapa
    let map = null;
    let marker = null;
    let mapInitialized = false;
    let pendingUpdates = [];
    let retryCount = 0;
    const MAX_RETRIES = 3;

    // Configuración inicial
    let initialLat = @json($selectedAddressData['latitude'] ?? null);
    let initialLng = @json($selectedAddressData['longitude'] ?? null);
    let showMapInitial = @json($show_map);

    // Función principal de inicialización
    window.initMap = function () {
        console.log('🗺️ Inicializando sistema de mapas...');
        
        // Esperar a que Livewire esté listo
        if (typeof Livewire !== 'undefined') {
            setupLivewireListeners();
        } else {
            document.addEventListener('livewire:initialized', setupLivewireListeners);
        }
    }

    function setupLivewireListeners() {
        console.log('📡 Configurando listeners de Livewire...');
        
        try {
            // Configurar datos iniciales
            if (typeof Livewire.find !== 'undefined') {
                const livewireComponent = Livewire.find('{{ $this->getId() }}');
                if (livewireComponent) {
                    livewireComponent.dispatch('setInitialAddressData', {
                        initialData: @json($selectedAddressData)
                    });
                }
            }

            // Listener para actualización del mapa
            Livewire.on('updateMap', handleMapUpdate);
            
            // Listener para actualización con delay
            Livewire.on('delayedMapUpdate', handleDelayedMapUpdate);

            // Listener para resetear el mapa
            Livewire.on('resetMap', handleMapReset);

            // Si debe mostrar el mapa inicialmente
            if (showMapInitial && coordenadasValidas(initialLat, initialLng)) {
                console.log('🎯 Mostrando mapa inicial...');
                setTimeout(() => {
                    crearOActualizarMapa(initialLat, initialLng, true);
                }, 500);
            }

        } catch (error) {
            console.error('❌ Error configurando listeners:', error);
        }
    }

    function handleMapUpdate(eventData) {
        console.log('📍 Recibida actualización de mapa:', eventData);
        
        if (!validarDatosEvento(eventData)) {
            console.warn('⚠️ Datos de evento inválidos');
            return;
        }

        const data = eventData[0];
        const newLat = parseFloat(data.lat);
        const newLng = parseFloat(data.lng);
        const force = data.force || false;

        // Validar coordenadas
        if (!coordenadasValidas(newLat, newLng)) {
            console.warn('⚠️ Coordenadas inválidas:', { newLat, newLng });
            return;
        }

        // Agregar a la cola de actualizaciones pendientes
        pendingUpdates.push({ lat: newLat, lng: newLng, force });
        
        // Procesar actualizaciones
        procesarActualizacionesPendientes();
    }

    function handleDelayedMapUpdate(eventData) {
        if (!validarDatosEvento(eventData)) return;
        
        const data = eventData[0];
        const delay = data.delay || 1000;
        
        console.log(`⏰ Programando actualización con delay de ${delay}ms`);
        
        setTimeout(() => {
            handleMapUpdate([{ lat: data.lat, lng: data.lng, force: true }]);
        }, delay);
    }

    function handleMapReset() {
        console.log('🔄 Reseteando mapa...');
        limpiarMapa();
        ocultarMapa();
    }

    function procesarActualizacionesPendientes() {
        if (pendingUpdates.length === 0) return;
        
        // Tomar la última actualización
        const ultimaActualizacion = pendingUpdates.pop();
        pendingUpdates = []; // Limpiar otras actualizaciones pendientes
        
        // Ejecutar con un pequeño delay para evitar problemas de timing
        setTimeout(() => {
            crearOActualizarMapa(ultimaActualizacion.lat, ultimaActualizacion.lng, ultimaActualizacion.force);
        }, 100);
    }

    function coordenadasValidas(lat, lng) {
        return lat !== null && lng !== null &&
               !isNaN(lat) && !isNaN(lng) &&
               lat !== 0 && lng !== 0 &&
               Math.abs(lat) <= 90 && Math.abs(lng) <= 180;
    }

    function validarDatosEvento(eventData) {
        return Array.isArray(eventData) && 
               eventData.length > 0 && 
               eventData[0] && 
               typeof eventData[0].lat !== 'undefined' && 
               typeof eventData[0].lng !== 'undefined';
    }

    function crearOActualizarMapa(lat, lng, force = false) {
        console.log(`🗺️ Creando/actualizando mapa: (${lat}, ${lng}), force: ${force}`);
        
        const mapElement = document.getElementById('map');
        const loadingElement = document.getElementById('map-loading');
        
        if (!mapElement) {
            console.error('❌ Elemento del mapa no encontrado');
            return;
        }

        // Mostrar el contenedor del mapa y ocultar loading
        mostrarMapa();

        const centro = { lat: parseFloat(lat), lng: parseFloat(lng) };

        // Si el mapa ya existe y no se fuerza recreación, solo actualizar
        if (map && marker && mapInitialized && !force) {
            try {
                console.log('🔄 Actualizando mapa existente...');
                marker.setPosition(centro);
                map.setCenter(centro);
                map.setZoom(16);
                
                // Forzar resize para asegurar que se muestre correctamente
                setTimeout(() => {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(centro);
                }, 100);
                
                return;
            } catch (error) {
                console.warn('⚠️ Error actualizando mapa existente, recreando...', error);
                limpiarMapa();
            }
        }

        // Crear nuevo mapa
        crearNuevoMapa(lat, lng, mapElement);
    }

    function crearNuevoMapa(lat, lng, mapElement) {
        console.log('🆕 Creando nuevo mapa...');
        
        try {
            // Limpiar cualquier mapa anterior
            limpiarMapa();
            
            // Asegurar dimensiones correctas
            verificarDimensiones(mapElement);
            
            const centro = { lat: parseFloat(lat), lng: parseFloat(lng) };
            
            // Crear el mapa con configuración robusta
            map = new google.maps.Map(mapElement, {
                center: centro,
                zoom: 16,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                gestureHandling: 'cooperative',
                disableDefaultUI: false,
                clickableIcons: false,
                backgroundColor: '#e5e3df',
                styles: []
            });
            
            // Crear marcador personalizado
            marker = new google.maps.Marker({
                position: centro,
                map: map,
                draggable: true,
                title: 'Arrastra para ajustar la ubicación exacta',
                animation: google.maps.Animation.DROP,
                icon: {
                    url: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="%23DC2626"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18.364 4.636a9 9 0 0 1 .203 12.519l-.203 .21l-4.243 4.242a3 3 0 0 1 -4.097 .135l-.144 -.135l-4.244 -4.243a9 9 0 0 1 12.728 -12.728zm-6.364 3.364a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" /></svg>',
                    scaledSize: new google.maps.Size(32, 32),
                    anchor: new google.maps.Point(16, 32)
                }
            });
            
            // Listener para cuando se arrastra el marcador
            marker.addListener('dragend', function() {
                const nuevaLat = marker.getPosition().lat();
                const nuevaLng = marker.getPosition().lng();
                
                console.log('📍 Marcador movido a:', { lat: nuevaLat, lng: nuevaLng });
                
                // Notificar a Livewire
                try {
                    Livewire.dispatch('mapLocationUpdated', { 
                        lat: nuevaLat, 
                        lng: nuevaLng 
                    });
                } catch (error) {
                    console.error('❌ Error enviando ubicación a Livewire:', error);
                }
            });

            // Esperar a que el mapa se cargue completamente
            google.maps.event.addListenerOnce(map, 'idle', function() {
                console.log('✅ Mapa cargado completamente');
                mapInitialized = true;
                retryCount = 0;
                
                // Forzar resize final
                setTimeout(() => {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(centro);
                    
                    // Ocultar definitivamente el loading
                    const loadingElement = document.getElementById('map-loading');
                    if (loadingElement) {
                        loadingElement.style.display = 'none';
                    }
                }, 200);
            });

            // Listener para errores de carga
            google.maps.event.addListener(map, 'tilesloaded', function() {
                console.log('🎯 Tiles del mapa cargados');
            });
            
        } catch (error) {
            console.error('❌ Error creando mapa:', error);
            
            // Reintentar si no hemos superado el límite
            if (retryCount < MAX_RETRIES) {
                retryCount++;
                console.log(`🔄 Reintentando crear mapa (${retryCount}/${MAX_RETRIES})...`);
                setTimeout(() => {
                    crearNuevoMapa(lat, lng, mapElement);
                }, 1000 * retryCount);
            } else {
                console.error('❌ No se pudo crear el mapa después de varios intentos');
                mostrarErrorMapa();
            }
        }
    }

    function verificarDimensiones(mapElement) {
        // Asegurar que el elemento tenga las dimensiones correctas
        mapElement.style.width = '100%';
        mapElement.style.height = '256px';
        mapElement.style.minHeight = '256px';
        mapElement.style.display = 'block';
        mapElement.style.position = 'relative';
        mapElement.style.backgroundColor = '#e5e3df';
        mapElement.style.borderRadius = '6px';
        
        // Forzar reflow
        mapElement.offsetHeight;
    }

    function mostrarMapa() {
        const mapElement = document.getElementById('map');
        const loadingElement = document.getElementById('map-loading');
        
        if (mapElement) {
            mapElement.classList.remove('hidden');
            mapElement.style.display = 'block';
        }
        
        // Mantener el loading visible hasta que el mapa esté completamente cargado
        if (loadingElement && !mapInitialized) {
            loadingElement.style.display = 'flex';
        }
    }

    function ocultarMapa() {
        const mapElement = document.getElementById('map');
        const loadingElement = document.getElementById('map-loading');
        
        if (mapElement) {
            mapElement.classList.add('hidden');
            mapElement.style.display = 'none';
        }
        
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }

    function limpiarMapa() {
        console.log('🧹 Limpiando mapa...');
        
        try {
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
            
            if (map) {
                // Limpiar todos los listeners
                google.maps.event.clearInstanceListeners(map);
                map = null;
            }
            
            mapInitialized = false;
            retryCount = 0;
            
            // Limpiar el contenedor del mapa
            const mapElement = document.getElementById('map');
            if (mapElement) {
                mapElement.innerHTML = '';
            }
            
        } catch (error) {
            console.warn('⚠️ Error limpiando mapa:', error);
        }
    }

    function mostrarErrorMapa() {
        const mapElement = document.getElementById('map');
        const loadingElement = document.getElementById('map-loading');
        
        if (loadingElement) {
            loadingElement.innerHTML = `
                <div class="text-center">
                    <svg class="h-8 w-8 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-xs text-red-600 dark:text-red-400">Error cargando el mapa</p>
                    <button onclick="location.reload()" class="text-xs text-blue-600 hover:underline mt-1">Recargar página</button>
                </div>
            `;
        }
    }

    // Función de limpieza cuando se cierra la página
    window.addEventListener('beforeunload', function() {
        limpiarMapa();
    });

    // Observer para detectar cambios en la visibilidad del contenedor del mapa
    function setupMapObserver() {
        const mapElement = document.getElementById('map');
        if (!mapElement) return;
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const isVisible = !mapElement.classList.contains('hidden');
                    if (isVisible && map && mapInitialized) {
                        // El mapa se hizo visible, forzar resize
                        setTimeout(() => {
                            google.maps.event.trigger(map, 'resize');
                            if (marker) {
                                map.setCenter(marker.getPosition());
                            }
                        }, 100);
                    }
                }
            });
        });
        
        observer.observe(mapElement, { attributes: true });
    }

    // Configurar observer cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', setupMapObserver);

</script>

<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap"
    onerror="console.error('❌ Error cargando Google Maps API'); mostrarErrorMapa();">
</script>
@endpush