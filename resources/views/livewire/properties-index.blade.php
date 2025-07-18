<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Incluimos el componente GlobalFilterNav y le pasamos los filtros actuales desde PropertiesIndex --}}
        {{-- Esto asegura que el GlobalFilterNav se inicialice con los valores de la URL --}}
        @livewire('global-filter-nav', [
            'locationSearch' => $currentFilters['locationSearch'],
            {{-- CAMBIO CLAVE AQUÍ: Pasar 'operationType' como string, no 'isForSale'/'isForRent' --}}
            'operationType' => $currentFilters['operationType'], // Pasa directamente el string 'sale', 'rent', 'all' o null
            'propertyTypeSlug' => $currentFilters['propertyTypeSlug'],
            'minPrice' => $currentFilters['minPrice'],
            'maxPrice' => $currentFilters['maxPrice'],
            'features' => $currentFilters['features'], // Pasamos los filtros dinámicos
            'minSuperficieConstruida' => $currentFilters['minSuperficieConstruida'],
            'maxSuperficieConstruida' => $currentFilters['maxSuperficieConstruida'],
            'minSuperficieTerreno' => $currentFilters['minSuperficieTerreno'],
            'maxSuperficieTerreno' => $currentFilters['maxSuperficieTerreno'],
        ])

        <div class="bg-white overflow-hidden sm:rounded-lg p-6 mt-4">
            {{-- Incluimos el componente ShowProperties y le pasamos las propiedades filtradas junto con el estado de carga --}}
            {{-- Añadimos wire:key para forzar la actualización del componente ShowProperties cuando los filtros cambian --}}
            @livewire('show-properties', [
                'properties' => $properties,
                'isLoading' => $isLoading
            ], key('show-properties-list-' . md5(json_encode($currentFilters))))
        </div>
    </div>
</div>
