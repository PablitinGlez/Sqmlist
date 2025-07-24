<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\PropertyType;
use App\Models\Feature;
use App\Models\FeatureSection;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Colonia;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
        Log::info('GlobalFilterNav: mount called with direct props.', [
            'locationSearch' => $locationSearch,
            'operationType' => $operationType,
            'propertyTypeSlug' => $propertyTypeSlug,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'features' => $features,
            'minSuperficieConstruida' => $minSuperficieConstruida,
            'maxSuperficieConstruida' => $maxSuperficieConstruida,
            'minSuperficieTerreno' => $minSuperficieTerreno,
            'maxSuperficieTerreno' => $maxSuperficieTerreno,
        ]);

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
        Log::info('GlobalFilterNav: allFeaturesCache loaded.', ['count' => $this->allFeaturesCache->count()]);

        $this->filteredFeatures = collect();

        $this->loadDynamicFeatures($this->selectedPropertyTypeSlug);

        $this->updateOperationDisplay();
        $this->updatePropertyTypeDisplay($this->selectedPropertyTypeSlug);
        $this->updatePriceDisplay();

        Log::info('GlobalFilterNav: mount finished.');
    }

    public function updatedLocationSearch(): void
    {
        Log::info('GlobalFilterNav: updatedLocationSearch called.', ['locationSearch' => $this->locationSearch]);
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
        Log::info('GlobalFilterNav: Generated location suggestions.', ['suggestions' => $this->locationSuggestions]);
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
        Log::info('GlobalFilterNav: updatedIsForSale called.', ['isForSale' => $this->isForSale]);
        if (!$this->isForSale && !$this->isForRent) {
            $this->isForRent = true;
        }
        $this->updateOperationDisplay();
        $this->emitFilters();
    }

    public function updatedIsForRent(): void
    {
        Log::info('GlobalFilterNav: updatedIsForRent called.', ['isForRent' => $this->isForRent]);
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
        Log::info('GlobalFilterNav: operationDisplay updated.', ['display' => $this->operationDisplay]);
    }

    public function updatedSelectedPropertyTypeSlug(?string $slug = null): void
    {
        Log::info('GlobalFilterNav: updatedSelectedPropertyTypeSlug called.', ['slug' => $slug]);
        $this->updatePropertyTypeDisplay($slug);
        $this->loadDynamicFeatures($slug);
        $this->emitFilters();
    }

    private function updatePropertyTypeDisplay(?string $slug = null): void
    {
        if (!isset($this->propertyTypes) || $this->propertyTypes->isEmpty()) {
            $this->propertyTypes = PropertyType::orderBy('order')->get();
            Log::info('updatePropertyTypeDisplay: Re-loaded propertyTypes as it was empty or not set.');
        }

        if ($slug && $slug !== '') {
            $trimmedSlug = strtolower(trim($slug));
            $propertyType = $this->propertyTypes->first(function ($type) use ($trimmedSlug) {
                return strtolower(trim($type->slug)) === $trimmedSlug;
            });

            if ($propertyType) {
                $this->propertyTypeDisplayName = $propertyType->name;
                Log::info('updatePropertyTypeDisplay: Found property type.', ['name' => $propertyType->name]);
            } else {
                $this->propertyTypeDisplayName = 'Tipo de Propiedad';
                Log::warning('updatePropertyTypeDisplay: Property type not found for slug.', ['slug' => $slug, 'normalized_slug' => $trimmedSlug, 'available_slugs' => $this->propertyTypes->pluck('slug')->toArray()]);
            }
        } else {
            $this->propertyTypeDisplayName = 'Tipo de Propiedad';
            Log::info('updatePropertyTypeDisplay: Slug is null or empty, setting to default display name.');
        }
        Log::info('GlobalFilterNav: propertyTypeDisplayName updated.', ['display' => $this->propertyTypeDisplayName]);
    }

    public function updatedMinPrice(): void
    {
        Log::info('GlobalFilterNav: updatedMinPrice called.', ['minPrice' => $this->minPrice]);
        $this->updatePriceDisplay();
        $this->emitFilters();
    }

    public function updatedMaxPrice(): void
    {
        Log::info('GlobalFilterNav: updatedMaxPrice called.', ['maxPrice' => $this->maxPrice]);
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
        Log::info('GlobalFilterNav: priceDisplay updated.', ['display' => $this->priceDisplay]);
    }

    public function setRecamaras($value): void
    {
        Log::info('GlobalFilterNav: setRecamaras called.', ['value' => $value]);
        $this->filters['num_recamaras'] = $value;
        $this->emitFilters();
    }

    public function setBanos($value): void
    {
        Log::info('GlobalFilterNav: setBanos called.', ['value' => $value]);
        $this->filters['num_banos'] = $value;
        $this->emitFilters();
    }

    public function setEstacionamientos($value): void
    {
        Log::info('GlobalFilterNav: setEstacionamientos called.', ['value' => $value]);
        $this->filters['num_estacionamientos'] = $value;
        $this->emitFilters();
    }

    public function updatedFilters(): void
    {
        Log::info('GlobalFilterNav: updatedFilters called.', ['filters' => $this->filters]);
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

    private function initializeDefaultFilters(): void
    {
        Log::info('GlobalFilterNav: initializeDefaultFilters called.');
        $this->filters = [];
        $this->recamarasOptions = collect();
        $this->banosOptions = collect();
        $this->estacionamientosOptions = collect();
        $this->amenityFeatures = collect();
        $this->filteredFeatures = collect();

        if ($this->allFeaturesCache === null) {
            $this->allFeaturesCache = Feature::where('is_filterable', true)
                ->with('featureSection', 'propertyTypes')
                ->orderBy('order')
                ->get() ?? collect();
            Log::warning('GlobalFilterNav: allFeaturesCache was null in initializeDefaultFilters, re-loaded.');
        }

        $generalAndAmenityFeatures = $this->allFeaturesCache->filter(function ($feature) {
            return $feature->featureSection && in_array($feature->featureSection->slug, ['caracteristicas_generales', 'amenidades']);
        })->sortBy('order');

        foreach ($generalAndAmenityFeatures as $feature) {
            if (isset($this->filters[$feature->slug])) {
            } elseif ($feature->input_type === 'boolean') {
                $this->filters[$feature->slug] = false;
            } else {
                $this->filters[$feature->slug] = null;
            }

            if ($feature->featureSection && $feature->featureSection->slug === 'caracteristicas_generales') {
                if ($feature->slug === 'num_recamaras') {
                    $this->recamarasOptions = collect([
                        (object)['value' => 'Todos', 'label' => 'Todos'],
                        (object)['value' => 1, 'label' => '1'],
                        (object)['value' => 2, 'label' => '2'],
                        (object)['value' => 3, 'label' => '3'],
                        (object)['value' => 4, 'label' => '4+'],
                    ]);
                    if (!isset($this->filters[$feature->slug])) {
                        $this->filters[$feature->slug] = 'Todos';
                    }
                } elseif ($feature->slug === 'num_banos') {
                    $this->banosOptions = collect([
                        (object)['value' => 'Todos', 'label' => 'Todos'],
                        (object)['value' => 1, 'label' => '1'],
                        (object)['value' => 2, 'label' => '2'],
                        (object)['value' => 3, 'label' => '3'],
                        (object)['value' => 4, 'label' => '4+'],
                    ]);
                    if (!isset($this->filters[$feature->slug])) {
                        $this->filters[$feature->slug] = 'Todos';
                    }
                } elseif ($feature->slug === 'num_estacionamientos') {
                    $this->estacionamientosOptions = collect([
                        (object)['value' => 'Todos', 'label' => 'Todos'],
                        (object)['value' => 1, 'label' => '1'],
                        (object)['value' => 2, 'label' => '2'],
                        (object)['value' => 3, 'label' => '3'],
                        (object)['value' => 4, 'label' => '4+'],
                    ]);
                    if (!isset($this->filters[$feature->slug])) {
                        $this->filters[$feature->slug] = 'Todos';
                    }
                }
            } elseif ($feature->featureSection && $feature->featureSection->slug === 'amenidades') {
                $this->amenityFeatures->push($feature);
            }
        }

        Log::info('GlobalFilterNav: initializeDefaultFilters finished.', ['filters' => $this->filters]);
    }

    private function loadDynamicFeatures(?string $propertyTypeSlug = null): void
    {
        Log::info('GlobalFilterNav: loadDynamicFeatures called.', ['propertyTypeSlug' => $propertyTypeSlug]);
        $oldFilters = $this->filters;
        $this->filters = [];

        $this->recamarasOptions = collect();
        $this->banosOptions = collect();
        $this->estacionamientosOptions = collect();
        $this->amenityFeatures = collect();
        $this->filteredFeatures = collect();

        $this->minSuperficieConstruida = null;
        $this->maxSuperficieConstruida = null;
        $this->minSuperficieTerreno = null;
        $this->maxSuperficieTerreno = null;

        if (is_null($propertyTypeSlug) || $propertyTypeSlug === '') {
            $this->initializeDefaultFilters();
            Log::info('GlobalFilterNav: loadDynamicFeatures reverted to default filters.');
            foreach ($oldFilters as $key => $value) {
                if (in_array($key, ['num_recamaras', 'num_banos', 'num_estacionamientos', 'has_alberca', 'is_amueblado', 'has_jardines', 'permite_mascotas', 'has_sotano', 'has_terraza', 'has_zona_privada', 'has_chimenea', 'has_cuarto_servicio', 'has_gimnasio', 'has_aire_acondicionado', 'has_calefaccion', 'has_cisterna', 'has_gas_natural', 'has_lavanderia', 'has_seguridad_privada', 'has_telefonia', 'circuito_cerrado_tv', 'fibra_optica', 'planta_emergencia', 'sistema_contra_incendio', 'vigilancia'])) {
                    $this->filters[$key] = $value;
                }
            }
            return;
        }

        if ($this->allFeaturesCache === null) {
            $this->allFeaturesCache = Feature::where('is_filterable', true)
                ->with('featureSection', 'propertyTypes')
                ->orderBy('order')
                ->get() ?? collect();
            Log::warning('GlobalFilterNav: allFeaturesCache was null in loadDynamicFeatures, re-loaded.');
        }

        $this->filteredFeatures = $this->allFeaturesCache->filter(function ($feature) use ($propertyTypeSlug) {
            $isAssociatedWithType = $feature->propertyTypes->contains('slug', $propertyTypeSlug);
            $isGeneralOrAmenity = $feature->featureSection && in_array($feature->featureSection->slug, ['caracteristicas_generales', 'amenidades']);
            return $isAssociatedWithType && $isGeneralOrAmenity;
        })->sortBy(function ($feature) use ($propertyTypeSlug) {
            $pivot = $feature->propertyTypes->firstWhere('slug', $propertyTypeSlug)?->pivot;
            return $pivot ? $pivot->order_for_type : 999;
        });

        foreach ($this->filteredFeatures as $feature) {
            if (isset($oldFilters[$feature->slug])) {
                $this->filters[$feature->slug] = $oldFilters[$feature->slug];
            } elseif ($feature->input_type === 'boolean') {
                $this->filters[$feature->slug] = false;
            } else {
                $this->filters[$feature->slug] = null;
            }

            if ($feature->featureSection && $feature->featureSection->slug === 'caracteristicas_generales') {
                if ($feature->slug === 'num_recamaras') {
                    $this->recamarasOptions = collect([
                        (object)['value' => 'Todos', 'label' => 'Todos'],
                        (object)['value' => 1, 'label' => '1'],
                        (object)['value' => 2, 'label' => '2'],
                        (object)['value' => 3, 'label' => '3'],
                        (object)['value' => 4, 'label' => '4+'],
                    ]);
                    if (!isset($oldFilters[$feature->slug])) {
                        $this->filters[$feature->slug] = 'Todos';
                    }
                } elseif ($feature->slug === 'num_banos') {
                    $this->banosOptions = collect([
                        (object)['value' => 'Todos', 'label' => 'Todos'],
                        (object)['value' => 1, 'label' => '1'],
                        (object)['value' => 2, 'label' => '2'],
                        (object)['value' => 3, 'label' => '3'],
                        (object)['value' => 4, 'label' => '4+'],
                    ]);
                    if (!isset($oldFilters[$feature->slug])) {
                        $this->filters[$feature->slug] = 'Todos';
                    }
                } elseif ($feature->slug === 'num_estacionamientos') {
                    $this->estacionamientosOptions = collect([
                        (object)['value' => 'Todos', 'label' => 'Todos'],
                        (object)['value' => 1, 'label' => '1'],
                        (object)['value' => 2, 'label' => '2'],
                        (object)['value' => 3, 'label' => '3'],
                        (object)['value' => 4, 'label' => '4+'],
                    ]);
                    if (!isset($oldFilters[$feature->slug])) {
                        $this->filters[$feature->slug] = 'Todos';
                    }
                }
            } elseif ($feature->featureSection && $feature->featureSection->slug === 'amenidades') {
                $this->amenityFeatures->push($feature);
            }
        }
        Log::info('GlobalFilterNav: loadDynamicFeatures finished.', ['filteredFeaturesCount' => $this->filteredFeatures->count(), 'filters' => $this->filters]);
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

        $allFilters = [
            'locationSearch' => $this->locationSearch,
            'operation_type' => $operationTypeToEmit,
            'property_type_slug' => $this->selectedPropertyTypeSlug,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'features' => $this->filters,
            'minSuperficieConstruida' => $this->minSuperficieConstruida,
            'maxSuperficieConstruida' => $this->maxSuperficieConstruida,
            'minSuperficieTerreno' => $this->minSuperficieTerreno,
            'maxSuperficieTerreno' => $this->maxSuperficieTerreno,
        ];

        $cleanedFilters = [];
        foreach ($allFilters as $key => $value) {
            if ($key === 'features') {
                $cleanedFeatures = [];
                foreach ($value as $featureSlug => $featureValue) {
                    if (is_bool($featureValue)) {
                        $cleanedFeatures[$featureSlug] = $featureValue;
                    } elseif ($featureValue !== null && $featureValue !== '' && $featureValue !== 'Todos') {
                        $cleanedFeatures[$featureSlug] = $featureValue;
                    }
                }
                if (!empty($cleanedFeatures)) {
                    $cleanedFilters[$key] = $cleanedFeatures;
                }
            } elseif (in_array($key, ['minPrice', 'maxPrice', 'minSuperficieConstruida', 'maxSuperficieConstruida', 'minSuperficieTerreno', 'maxSuperficieTerreno'])) {
                if ($value !== null) {
                    $cleanedFilters[$key] = $value;
                }
            } elseif ($key === 'locationSearch') {
                $cleanedFilters[$key] = $value;
            } elseif ($value !== null && $value !== '' && (!is_array($value) || !empty($value))) {
                $cleanedFilters[$key] = $value;
            }
        }

        Log::info('GlobalFilterNav: globalFiltersUpdated dispatched.', ['filters' => $cleanedFilters]);
        $this->dispatch('globalFiltersUpdated', $cleanedFilters);
    }

    public function applyFilters()
    {
        $this->dispatch('filtersUpdated', $this->getFilters());
    }

    public function render()
    {
        Log::debug('GlobalFilterNav: render called. Current filters state:', ['filters' => $this->filters]);
        return view('livewire.global-filter-nav');
    }
}
