<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PropertyType;
use App\Models\Feature;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Colonia;
use Illuminate\Support\Collection;

class GlobalFilterNav extends Component
{
    public ?string $locationSearch = '';
    public array $locationSuggestions = [];
    public bool $showLocationSuggestions = false;

    public bool $isForSale = false;
    public bool $isForRent = false;
    public string $operationDisplay = 'Tipo de Operación';

    public Collection $propertyTypes;
    public ?string $selectedPropertyTypeSlug = null;
    public string $propertyTypeDisplayName = 'Tipo de Propiedad';

    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public string $priceDisplay = 'Rango de Precio';

    public Collection $filteredFeatures;
    public array $filters = [];

    public Collection $recamarasOptions;
    public Collection $banosOptions;
    public Collection $estacionamientosOptions;
    public Collection $amenityFeatures;

    public ?float $minSuperficieConstruida = null;
    public ?float $maxSuperficieConstruida = null;
    public ?float $minSuperficieTerreno = null;
    public ?float $maxSuperficieTerreno = null;

    public ?Collection $allFeaturesCache = null;

    public function mount(
        ?string $locationSearch = '',
        ?string $operationType = null,
        ?string $propertyTypeSlug = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        array $features = [],
        ?float $minSuperficieConstruida = null,
        ?float $maxSuperficieConstruida = null,
        ?float $minSuperficieTerreno = null,
        ?float $maxSuperficieTerreno = null
    ): void {
        $this->locationSearch = $locationSearch;
        $this->isForSale = ($operationType === 'sale' || $operationType === null || $operationType === 'all');
        $this->isForRent = ($operationType === 'rent' || $operationType === null || $operationType === 'all');

        $this->selectedPropertyTypeSlug = $propertyTypeSlug;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->filters = $features;

        $this->minSuperficieConstruida = $minSuperficieConstruida;
        $this->maxSuperficieConstruida = $maxSuperficieConstruida;
        $this->minSuperficieTerreno = $minSuperficieTerreno;
        $this->maxSuperficieTerreno = $maxSuperficieTerreno;

        $this->propertyTypes = PropertyType::orderBy('order')->get();

        $this->allFeaturesCache = Feature::where('is_filterable', true)
            ->with('featureSection', 'propertyTypes')
            ->orderBy('order')
            ->get() ?? collect();

        $this->filteredFeatures = collect();

        $this->loadDynamicFeatures($this->selectedPropertyTypeSlug);

        $this->updateOperationDisplay();
        $this->updatePropertyTypeDisplay($this->selectedPropertyTypeSlug);
        $this->updatePriceDisplay();
    }

    public function updatedLocationSearch(): void
    {
        $this->generateLocationSuggestions();
        $this->emitFilters();
    }

    private function generateLocationSuggestions(): void
    {
        $this->locationSuggestions = [];
        $searchTerm = trim($this->locationSearch);

        if (strlen($searchTerm) < 2) {
            $this->showLocationSuggestions = false;
            return;
        }

        $suggestions = collect();

        $colonias = Colonia::where('name', 'like', '%' . $searchTerm . '%')
            ->with('municipality.state')
            ->limit(5)
            ->get();
        foreach ($colonias as $colonia) {
            $fullAddress = [];
            if ($colonia->name) $fullAddress[] = $colonia->name;
            if ($colonia->municipality) $fullAddress[] = $colonia->municipality->name;
            if ($colonia->municipality && $colonia->municipality->state) $fullAddress[] = $colonia->municipality->state->name;
            if ($colonia->postal_code) $fullAddress[] = 'CP: ' . $colonia->postal_code;
            $suggestions->push(implode(', ', array_filter($fullAddress)));
        }

        $municipalities = Municipality::where('name', 'like', '%' . $searchTerm . '%')
            ->with('state')
            ->limit(5 - $suggestions->count())
            ->get();
        foreach ($municipalities as $municipality) {
            $fullAddress = [];
            if ($municipality->name) $fullAddress[] = $municipality->name;
            if ($municipality->state) $fullAddress[] = $municipality->state->name;
            $suggestions->push(implode(', ', array_filter($fullAddress)));
        }

        $states = State::where('name', 'like', '%' . $searchTerm . '%')
            ->limit(5 - $suggestions->count())
            ->get();
        foreach ($states as $state) {
            $suggestions->push($state->name);
        }

        $this->locationSuggestions = $suggestions->unique()->take(10)->toArray();
        $this->showLocationSuggestions = !empty($this->locationSuggestions);
    }

    public function selectLocationSuggestion(string $suggestion): void
    {
        $this->locationSearch = $suggestion;
        $this->locationSuggestions = [];
        $this->showLocationSuggestions = false;
        $this->emitFilters();
    }

    public function updatedIsForSale(): void
    {
        if (!$this->isForSale && !$this->isForRent) {
            $this->isForRent = true;
        }
        $this->updateOperationDisplay();
        $this->emitFilters();
    }

    public function updatedIsForRent(): void
    {
        if (!$this->isForRent && !$this->isForSale) {
            $this->isForSale = true;
        }
        $this->updateOperationDisplay();
        $this->emitFilters();
    }

    private function updateOperationDisplay(): void
    {
        if ($this->isForSale && $this->isForRent) {
            $this->operationDisplay = 'Venta y Renta';
        } elseif ($this->isForSale) {
            $this->operationDisplay = 'Venta';
        } elseif ($this->isForRent) {
            $this->operationDisplay = 'Renta';
        } else {
            $this->operationDisplay = 'Tipo de Operación';
        }
    }

    public function updatedSelectedPropertyTypeSlug(?string $slug = null): void
    {
        $this->updatePropertyTypeDisplay($slug);
        $this->loadDynamicFeatures($slug);
        $this->emitFilters();
    }

    private function updatePropertyTypeDisplay(?string $slug = null): void
    {
        if (!isset($this->propertyTypes) || $this->propertyTypes->isEmpty()) {
            $this->propertyTypes = PropertyType::orderBy('order')->get();
        }

        if ($slug && $slug !== '') {
            $trimmedSlug = strtolower(trim($slug));
            $propertyType = $this->propertyTypes->first(fn($type) => strtolower(trim($type->slug)) === $trimmedSlug);

            if ($propertyType) {
                $this->propertyTypeDisplayName = $propertyType->name;
            } else {
                $this->propertyTypeDisplayName = 'Tipo de Propiedad';
            }
        } else {
            $this->propertyTypeDisplayName = 'Tipo de Propiedad';
        }
    }

    public function updatedMinPrice(): void
    {
        $this->updatePriceDisplay();
        $this->emitFilters();
    }

    public function updatedMaxPrice(): void
    {
        $this->updatePriceDisplay();
        $this->emitFilters();
    }

    private function updatePriceDisplay(): void
    {
        $min = $this->minPrice;
        $max = $this->maxPrice;

        if ($min !== null && $max !== null) {
            $this->priceDisplay = '$' . number_format($min) . ' - $' . number_format($max);
        } elseif ($min !== null) {
            $this->priceDisplay = 'Desde $' . number_format($min);
        } elseif ($max !== null) {
            $this->priceDisplay = 'Hasta $' . number_format($max);
        } else {
            $this->priceDisplay = 'Rango de Precio';
        }
    }

    public function setRecamaras($value): void
    {
        if (isset($this->filters['num_recamaras']) && $this->filters['num_recamaras'] == $value) {
            unset($this->filters['num_recamaras']);
        } else {
            $this->filters['num_recamaras'] = $value;
        }
        $this->emitFilters();
    }

    public function setBanos($value): void
    {
        if (isset($this->filters['num_banos']) && $this->filters['num_banos'] == $value) {
            unset($this->filters['num_banos']);
        } else {
            $this->filters['num_banos'] = $value;
        }
        $this->emitFilters();
    }

    public function setEstacionamientos($value): void
    {
        if (isset($this->filters['num_estacionamientos']) && $this->filters['num_estacionamientos'] == $value) {
            unset($this->filters['num_estacionamientos']);
        } else {
            $this->filters['num_estacionamientos'] = $value;
        }
        $this->emitFilters();
    }

    public function updatedFilters(): void
    {
        $this->emitFilters();
    }

    public function updatedMinSuperficieConstruida($value): void
    {
        $this->minSuperficieConstruida = $this->cleanNumericValue($value);
        $this->emitFilters();
    }

    public function updatedMaxSuperficieConstruida($value): void
    {
        $this->maxSuperficieConstruida = $this->cleanNumericValue($value);
        $this->emitFilters();
    }

    public function updatedMinSuperficieTerreno($value): void
    {
        $this->minSuperficieTerreno = $this->cleanNumericValue($value);
        $this->emitFilters();
    }

    public function updatedMaxSuperficieTerreno($value): void
    {
        $this->maxSuperficieTerreno = $this->cleanNumericValue($value);
        $this->emitFilters();
    }

    private function cleanNumericValue($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $stringValue = (string) $value;
        if (strpos($stringValue, '0') === 0 && strlen($stringValue) > 1 && strpos($stringValue, '.') === false) {
            $stringValue = ltrim($stringValue, '0');
        }
        if ($stringValue === '') {
            return null;
        }
        if (strpos($stringValue, '.') === 0) {
            $stringValue = '0' . $stringValue;
        }
        if (!is_numeric($stringValue)) {
            return null;
        }
        $numericValue = (float) $stringValue;
        if ($numericValue < 0) {
            return null;
        }
        if ($numericValue > 999999999) {
            return 999999999.0;
        }
        return $numericValue;
    }

    public function clearField(string $field): void
    {
        switch ($field) {
            case 'minPrice':
                $this->minPrice = null;
                $this->updatePriceDisplay();
                break;
            case 'maxPrice':
                $this->maxPrice = null;
                $this->updatePriceDisplay();
                break;
            case 'minSuperficieConstruida':
                $this->minSuperficieConstruida = null;
                break;
            case 'maxSuperficieConstruida':
                $this->maxSuperficieConstruida = null;
                break;
            case 'minSuperficieTerreno':
                $this->minSuperficieTerreno = null;
                break;
            case 'maxSuperficieTerreno':
                $this->maxSuperficieTerreno = null;
                break;
            case 'locationSearch':
                $this->locationSearch = '';
                $this->locationSuggestions = [];
                $this->showLocationSuggestions = false;
                break;
        }
        $this->emitFilters();
    }

    private function initializeFeatureOptions(): void
    {
        $this->recamarasOptions = collect([
            (object)['value' => 'Todos', 'label' => 'Todos'],
            (object)['value' => 1, 'label' => '1'],
            (object)['value' => 2, 'label' => '2'],
            (object)['value' => 3, 'label' => '3'],
            (object)['value' => 4, 'label' => '4+'],
        ]);
        $this->banosOptions = collect([
            (object)['value' => 'Todos', 'label' => 'Todos'],
            (object)['value' => 1, 'label' => '1'],
            (object)['value' => 2, 'label' => '2'],
            (object)['value' => 3, 'label' => '3'],
            (object)['value' => 4, 'label' => '4+'],
        ]);
        $this->estacionamientosOptions = collect([
            (object)['value' => 'Todos', 'label' => 'Todos'],
            (object)['value' => 1, 'label' => '1'],
            (object)['value' => 2, 'label' => '2'],
            (object)['value' => 3, 'label' => '3'],
            (object)['value' => 4, 'label' => '4+'],
        ]);
    }

    private function loadDynamicFeatures(?string $propertyTypeSlug = null): void
    {
        $oldFilters = $this->filters;
        $this->filters = [];

        $this->initializeFeatureOptions();
        $this->amenityFeatures = collect();
        $this->filteredFeatures = collect();

        
        $this->minSuperficieConstruida = null;
        $this->maxSuperficieConstruida = null;
        $this->minSuperficieTerreno = null;
        $this->maxSuperficieTerreno = null;

        if ($this->allFeaturesCache === null) {
            $this->allFeaturesCache = Feature::where('is_filterable', true)
                ->with('featureSection', 'propertyTypes')
                ->orderBy('order')
                ->get() ?? collect();
        }

        if (is_null($propertyTypeSlug) || $propertyTypeSlug === '') {
            $featuresToConsider = $this->allFeaturesCache->filter(function ($feature) {
                return $feature->featureSection && in_array($feature->featureSection->slug, ['caracteristicas_generales', 'amenidades']);
            })->sortBy('order');
        } else {
            $featuresToConsider = $this->allFeaturesCache->filter(function ($feature) use ($propertyTypeSlug) {
                $isAssociatedWithType = $feature->propertyTypes->contains('slug', $propertyTypeSlug);
                $isGeneralOrAmenity = $feature->featureSection && in_array($feature->featureSection->slug, ['caracteristicas_generales', 'amenidades']);
                return $isAssociatedWithType && $isGeneralOrAmenity;
            })->sortBy(function ($feature) use ($propertyTypeSlug) {
                $pivot = $feature->propertyTypes->firstWhere('slug', $propertyTypeSlug)?->pivot;
                return $pivot ? $pivot->order_for_type : 999;
            });
        }

        foreach ($featuresToConsider as $feature) {
       
            if (isset($oldFilters[$feature->slug])) {
                $this->filters[$feature->slug] = $oldFilters[$feature->slug];
            } elseif ($feature->input_type === 'boolean') {
                $this->filters[$feature->slug] = false;
            } else {
                $this->filters[$feature->slug] = null; 
            }

           
            if ($feature->slug === 'num_recamaras' && !isset($oldFilters[$feature->slug])) {
                $this->filters[$feature->slug] = 'Todos';
            } elseif ($feature->slug === 'num_banos' && !isset($oldFilters[$feature->slug])) {
                $this->filters[$feature->slug] = 'Todos';
            } elseif ($feature->slug === 'num_estacionamientos' && !isset($oldFilters[$feature->slug])) {
                $this->filters[$feature->slug] = 'Todos';
            }

            if ($feature->featureSection && $feature->featureSection->slug === 'amenidades') {
                $this->amenityFeatures->push($feature);
            }
        }
        $this->filteredFeatures = $featuresToConsider;
    }

    protected function emitFilters(): void
    {
        $operationTypeToEmit = null;

        if ($this->isForSale && $this->isForRent) {
            $operationTypeToEmit = 'all';
        } elseif ($this->isForSale) {
            $operationTypeToEmit = 'sale';
        } elseif ($this->isForRent) {
            $operationTypeToEmit = 'rent';
        }

        $cleanedFeatures = [];
        foreach ($this->filters as $featureSlug => $featureValue) {
            if (is_bool($featureValue)) {
                if ($featureValue === true) {
                    $cleanedFeatures[$featureSlug] = $featureValue;
                }
            } elseif ($featureValue !== null && $featureValue !== '' && $featureValue !== 'Todos') {
                $cleanedFeatures[$featureSlug] = $featureValue;
            }
        }

        $allFilters = [
            'locationSearch' => $this->locationSearch ?: '',
            'operation_type' => $operationTypeToEmit,
            'property_type_slug' => $this->selectedPropertyTypeSlug ?: null,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'features' => $cleanedFeatures,
            'minSuperficieConstruida' => $this->minSuperficieConstruida,
            'maxSuperficieConstruida' => $this->maxSuperficieConstruida,
            'minSuperficieTerreno' => $this->minSuperficieTerreno,
            'maxSuperficieTerreno' => $this->maxSuperficieTerreno,
        ];

        $this->dispatch('globalFiltersUpdated', $allFilters);
    }

    public function render()
    {
        return view('livewire.global-filter-nav');
    }
}
