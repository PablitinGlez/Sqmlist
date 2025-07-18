<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Property;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PropertiesIndex extends Component
{
    #[Url(as: 'ubicacion', except: '')]
    public ?string $locationSearch = '';

    #[Url(as: 'operacion', except: '')] // Cambiado de 'except: null' a 'except: '' '
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
    public bool $isLoading = true; // Nueva propiedad para manejar el estado de carga

    public function mount(): void
    {
        $this->loadProperties();
    }

    #[On('globalFiltersUpdated')]
    public function updateFilters(array $filters): void
    {
        Log::debug('PropertiesIndex: updateFilters llamado. Filtros recibidos:', $filters);
        // Activar loading antes de actualizar filtros
        $this->isLoading = true;
        
        if (isset($filters['locationSearch'])) {
            $this->locationSearch = $filters['locationSearch'];
        }
        if (array_key_exists('operation_type', $filters)) {
            $this->operationType = $filters['operation_type'];
        }
        if (isset($filters['property_type_slug'])) {
            $this->propertyTypeSlug = $filters['property_type_slug'];
        }
        if (isset($filters['minPrice'])) {
            $this->minPrice = $filters['minPrice'];
        }
        if (isset($filters['maxPrice'])) {
            $this->maxPrice = $filters['maxPrice'];
        }
        if (isset($filters['features'])) {
            $this->features = $filters['features'];
        }
        if (isset($filters['minSuperficieConstruida'])) {
            $this->minSuperficieConstruida = $filters['minSuperficieConstruida'];
        }
        if (isset($filters['maxSuperficieConstruida'])) {
            $this->maxSuperficieConstruida = $filters['maxSuperficieConstruida'];
        }
        if (isset($filters['minSuperficieTerreno'])) {
            $this->minSuperficieTerreno = $filters['minSuperficieTerreno'];
        }
        if (isset($filters['maxSuperficieTerreno'])) {
            $this->maxSuperficieTerreno = $filters['maxSuperficieTerreno'];
        }

        Log::debug('PropertiesIndex: updateFilters - Propiedades internas actualizadas.', [
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
        ]);

        $this->loadProperties();
    }

    /**
     * Carga las propiedades de la base de datos aplicando todos los filtros activos.
     * Esta es la lógica central de búsqueda.
     */
    public function loadProperties(): void
    {
        Log::debug('PropertiesIndex: Iniciando loadProperties.');
        $this->isLoading = true;
        Log::debug('PropertiesIndex: isLoading = true.');

        // LOG: Mostrar los filtros que se usarán para la consulta
        Log::debug('PropertiesIndex: Filtros actuales para la consulta:', [
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
        ]);

        $query = Property::query()->published();

        // Aplicar filtro de ubicación
        if (!empty($this->locationSearch)) {
            $searchTerm = '%' . $this->locationSearch . '%';
            $query->whereHas('address', function ($q) use ($searchTerm) {
                $q->where('street', 'like', $searchTerm)
                  ->orWhere('neighborhood_name', 'like', $searchTerm)
                  ->orWhere('municipality_name', 'like', $searchTerm)
                  ->orWhere('state_name', 'like', $searchTerm)
                  ->orWhere('postal_code', 'like', $searchTerm);
            });
            Log::debug('PropertiesIndex: Filtro de ubicación aplicado.', ['searchTerm' => $this->locationSearch]);
        }

        // Aplicar filtro de tipo de operación
        if ($this->operationType === 'sale' || $this->operationType === 'rent') {
            $query->where('operation_type', $this->operationType);
            Log::debug('PropertiesIndex: Filtro de operación aplicado.', ['operationType' => $this->operationType]);
        } elseif ($this->operationType === 'all' || is_null($this->operationType)) {
            // Cuando es 'all' o null, no se añade el filtro de operation_type
            Log::debug('PropertiesIndex: Filtro de operación NO aplicado (mostrando Venta y Renta).', ['operationType' => $this->operationType]);
        }


        // Aplicar filtro de tipo de propiedad
        if ($this->propertyTypeSlug) {
            $query->whereHas('propertyType', function ($q) {
                $q->where('slug', $this->propertyTypeSlug);
            });
            Log::debug('PropertiesIndex: Filtro de tipo de propiedad aplicado.', ['propertyTypeSlug' => $this->propertyTypeSlug]);
        }

        // Aplicar filtro de rango de precios
        if ($this->minPrice !== null) {
            $query->where('price', '>=', $this->minPrice);
            Log::debug('PropertiesIndex: Filtro de precio mínimo aplicado.', ['minPrice' => $this->minPrice]);
        }
        if ($this->maxPrice !== null) {
            $query->where('price', '<=', $this->maxPrice);
            Log::debug('PropertiesIndex: Filtro de precio máximo aplicado.', ['maxPrice' => $this->maxPrice]);
        }

        // Aplicar filtros de características dinámicas
        foreach ($this->features as $featureSlug => $featureValue) {
            if ($featureValue !== null && $featureValue !== '' && $featureValue !== 'Todos') {
                $query->whereHas('featureValues.feature', function ($q) use ($featureSlug, $featureValue) {
                    $q->where('slug', $featureSlug);
                    if (in_array($featureSlug, ['num_recamaras', 'num_banos', 'num_estacionamientos'])) {
                        if ($featureValue === '4+') {
                            $q->where('value', '>=', 4);
                        } else {
                            $q->where('value', (string) $featureValue);
                        }
                    } elseif (is_bool($featureValue)) {
                        $q->where('value', (string) (int) $featureValue);
                    } else {
                        $q->where('value', (string) $featureValue);
                    }
                });
                Log::debug('PropertiesIndex: Filtro de característica dinámica aplicado.', ['featureSlug' => $featureSlug, 'featureValue' => $featureValue]);
            }
        }

        // Aplicar filtros de superficie construida
        if ($this->minSuperficieConstruida !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_construccion_m2')
                  ->whereRaw('CAST(value AS DECIMAL(10,2)) >= ?', [$this->minSuperficieConstruida]);
            });
            Log::debug('PropertiesIndex: Filtro de superficie construida mínima aplicado.', ['minSuperficieConstruida' => $this->minSuperficieConstruida]);
        }
        if ($this->maxSuperficieConstruida !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_construccion_m2')
                  ->whereRaw('CAST(value AS DECIMAL(10,2)) <= ?', [$this->maxSuperficieConstruida]);
            });
            Log::debug('PropertiesIndex: Filtro de superficie construida máxima aplicado.', ['maxSuperficieConstruida' => $this->maxSuperficieConstruida]);
        }

        // Aplicar filtros de superficie de terreno
        if ($this->minSuperficieTerreno !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_terreno_m2')
                  ->whereRaw('CAST(value AS DECIMAL(10,2)) >= ?', [$this->minSuperficieTerreno]);
            });
            Log::debug('PropertiesIndex: Filtro de superficie terreno mínima aplicado.', ['minSuperficieTerreno' => $this->minSuperficieTerreno]);
        }
        if ($this->maxSuperficieTerreno !== null) {
            $query->whereHas('featureValues.feature', function ($q) {
                $q->where('slug', 'tamano_terreno_m2')
                  ->whereRaw('CAST(value AS DECIMAL(10,2)) <= ?', [$this->maxSuperficieTerreno]);
            });
            Log::debug('PropertiesIndex: Filtro de superficie terreno máxima aplicado.', ['maxSuperficieTerreno' => $this->maxSuperficieTerreno]);
        }

        $this->properties = $query->with([
            'images' => fn ($q) => $q->orderBy('order', 'asc'),
            'propertyType',
            'address',
            'featureValues.feature'
        ])->get();

        // LOG: Mostrar el número de propiedades obtenidas
        Log::debug('PropertiesIndex: Propiedades obtenidas.', ['count' => $this->properties->count()]);

        // LOG: Mostrar el tipo de operación de las primeras 5 propiedades (si las hay)
        if ($this->properties->isNotEmpty()) {
            $sampleOperations = $this->properties->take(5)->pluck('operation_type')->toArray();
            Log::debug('PropertiesIndex: Tipos de operación de muestra (primeras 5 propiedades):', $sampleOperations);
        } else {
            Log::debug('PropertiesIndex: No se obtuvieron propiedades.');
        }

        $this->isLoading = false;
        Log::debug('PropertiesIndex: isLoading = false. loadProperties finalizado.');
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
