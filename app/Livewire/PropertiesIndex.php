<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Property;
use Illuminate\Support\Collection;

class PropertiesIndex extends Component
{
    #[Url(as: 'ubicacion', except: '')]
    public ?string $locationSearch = '';

    #[Url(as: 'operacion', except: '')]
    public ?string $operationType = null;

    #[Url(as: 'tipo', except: '')]
    public ?string $propertyTypeSlug = null;

    #[Url(as: 'precio_min', except: null)]
    public ?float $minPrice = null;

    #[Url(as: 'precio_max', except: null)]
    public ?float $maxPrice = null;

    #[Url(as: 'caracteristicas', except: [])]
    public array $features = [];

    #[Url(as: 'sup_const_min', except: null)]
    public ?float $minSuperficieConstruida = null;

    #[Url(as: 'sup_const_max', except: null)]
    public ?float $maxSuperficieConstruida = null;

    #[Url(as: 'sup_terreno_min', except: null)]
    public ?float $minSuperficieTerreno = null;

    #[Url(as: 'sup_terreno_max', except: null)]
    public ?float $maxSuperficieTerreno = null;

    public Collection $properties;
    public bool $isLoading = true;

    public function mount(): void
    {
        $this->loadProperties();
    }

    #[On('globalFiltersUpdated')]
    public function updateFilters(array $filters): void
    {
        $this->isLoading = true;

        $this->locationSearch = $filters['locationSearch'] ?? $this->locationSearch;
        $this->operationType = $filters['operation_type'] ?? $this->operationType;
        $this->propertyTypeSlug = $filters['property_type_slug'] ?? $this->propertyTypeSlug;
        $this->minPrice = $filters['minPrice'] ?? $this->minPrice;
        $this->maxPrice = $filters['maxPrice'] ?? $this->maxPrice;
        $this->features = $filters['features'] ?? $this->features;
        $this->minSuperficieConstruida = $filters['minSuperficieConstruida'] ?? $this->minSuperficieConstruida;
        $this->maxSuperficieConstruida = $filters['maxSuperficieConstruida'] ?? $this->maxSuperficieConstruida;
        $this->minSuperficieTerreno = $filters['minSuperficieTerreno'] ?? $this->minSuperficieTerreno;
        $this->maxSuperficieTerreno = $filters['maxSuperficieTerreno'] ?? $this->maxSuperficieTerreno;

        $this->loadProperties();
    }

    public function loadProperties(): void
    {
        $this->isLoading = true;

        $query = Property::query()->published();

        if (!empty($this->locationSearch)) {
            $searchTerm = '%' . $this->locationSearch . '%';
            $query->whereHas('address', function ($q) use ($searchTerm) {
                $q->where('street', 'like', $searchTerm)
                    ->orWhere('neighborhood_name', 'like', $searchTerm)
                    ->orWhere('municipality_name', 'like', $searchTerm)
                    ->orWhere('state_name', 'like', $searchTerm)
                    ->orWhere('postal_code', 'like', $searchTerm);
            });
        }

        if ($this->operationType === 'sale' || $this->operationType === 'rent') {
            $query->where('operation_type', $this->operationType);
        }

        if ($this->propertyTypeSlug) {
            $query->whereHas('propertyType', function ($q) {
                $q->where('slug', $this->propertyTypeSlug);
            });
        }

        if ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
        }

        foreach ($this->features as $featureSlug => $featureValue) {
            if ($featureValue !== null && $featureValue !== '' && $featureValue !== 'Todos') {
                $query->whereHas('featureValues.feature', function ($q) use ($featureSlug, $featureValue) {
                    $q->where('slug', $featureSlug);

                    if (in_array($featureSlug, ['num_recamaras', 'num_banos', 'num_estacionamientos'])) {
                        if ($featureValue == 4) {
                            $q->whereRaw('CAST(value AS UNSIGNED) >= ?', [4]);
                        } else {
                            $q->whereRaw('CAST(value AS UNSIGNED) = ?', [(int) $featureValue]);
                        }
                    } elseif (is_bool($featureValue)) {
                        $q->where('value', (string) (int) $featureValue);
                    } else {
                        $q->where('value', (string) $featureValue);
                    }
                });
            }
        }

        if ($this->minSuperficieConstruida !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_construccion_m2')
                    ->whereRaw('CAST(value AS DECIMAL(10,2)) >= ?', [$this->minSuperficieConstruida]);
            });
        }
        if ($this->maxSuperficieConstruida !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_construccion_m2')
                    ->whereRaw('CAST(value AS DECIMAL(10,2)) <= ?', [$this->maxSuperficieConstruida]);
            });
        }

        if ($this->minSuperficieTerreno !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_terreno_m2')
                    ->whereRaw('CAST(value AS DECIMAL(10,2)) >= ?', [$this->minSuperficieTerreno]);
            });
        }
        if ($this->maxSuperficieTerreno !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_terreno_m2')
                    ->whereRaw('CAST(value AS DECIMAL(10,2)) <= ?', [$this->maxSuperficieTerreno]);
            });
        }

        $this->properties = $query->with([
            'images' => fn($q) => $q->orderBy('order', 'asc'),
            'propertyType',
            'address',
            'featureValues.feature'
        ])->get();

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.properties-index', [
            'properties' => $this->properties,
            'isLoading' => $this->isLoading,
            'currentFilters' => [
                'locationSearch' => $this->locationSearch,
                'operationType' => $this->operationType,
                'propertyTypeSlug' => $this->propertyTypeSlug,
                'minPrice' => $this->minPrice,
                'maxPrice' => $this->maxPrice,
                'features' => $this->features,
                'minSuperficieConstruida' => $this->minSuperficieConstruida,
                'maxSuperficieConstruida' => $this->maxSuperficieConstruida,
                'minSuperficieTerreno' => $this->minSuperficieTerreno,
                'maxSuperficieTerreno' => $this->maxSuperficieTerreno,
            ]
        ])->layout('layouts.app');
    }
}
