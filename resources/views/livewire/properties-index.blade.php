<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @livewire('global-filter-nav', [
            'locationSearch' => $currentFilters['locationSearch'],
            'operationType' => $currentFilters['operationType'],
            'propertyTypeSlug' => $currentFilters['propertyTypeSlug'],
            'minPrice' => $currentFilters['minPrice'],
            'maxPrice' => $currentFilters['maxPrice'],
            'features' => $currentFilters['features'],
            'minSuperficieConstruida' => $currentFilters['minSuperficieConstruida'],
            'maxSuperficieConstruida' => $currentFilters['maxSuperficieConstruida'],
            'minSuperficieTerreno' => $currentFilters['minSuperficieTerreno'],
            'maxSuperficieTerreno' => $currentFilters['maxSuperficieTerreno'],
        ])

        <div class="bg-white overflow-hidden sm:rounded-lg p-6 mt-4">
            @livewire('show-properties', [
                'properties' => $properties,
                'isLoading' => $isLoading
            ], key('show-properties-list-' . md5(json_encode($currentFilters))))
        </div>
    </div>
</div>