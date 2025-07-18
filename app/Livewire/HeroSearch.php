<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PropertyType;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Colonia;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Log; // Importar la fachada Log

class HeroSearch extends Component
{
    public ?string $locationSearch = '';
    public array $locationSuggestions = [];
    public bool $showSuggestions = false;

    public ?string $operationType = null;
    public ?string $selectedPropertyType = null;
    public Collection $propertyTypes;

    public function mount(?string $initialLocationSearch = null, ?string $initialPropertyType = null, ?string $initialOperationType = null): void
    {
        $this->locationSearch = $initialLocationSearch ?? '';
        $this->selectedPropertyType = $initialPropertyType ?? null;
        $this->operationType = $initialOperationType ?? 'sale'; // 'sale' será el valor por defecto

        $popularSlugs = [
            'casa',
            'departamento',
            'edificio',
            'terreno-comercial',
            'terreno-habitacional',
            'bodega-industrial',
        ];
        $this->propertyTypes = PropertyType::whereIn('slug', $popularSlugs)
                                           ->orderByRaw("FIELD(slug, '" . implode("','", $popularSlugs) . "')")
                                           ->get();
    }

    public function updatedLocationSearch(): void
    {
        $this->generateLocationSuggestions();
    }

    private function generateLocationSuggestions(): void
    {
        $this->locationSuggestions = [];
        $searchTerm = trim($this->locationSearch);

        if (strlen($searchTerm) < 2) {
            $this->showSuggestions = false;
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
        $this->showSuggestions = !empty($this->locationSuggestions);
    }

    public function selectSuggestion(string $suggestion): void
    {
        $this->locationSearch = $suggestion;
        $this->locationSuggestions = [];
        $this->showSuggestions = false;
    }

    public function setOperationType(string $type): void
    {
        $this->operationType = $type;
    }

    /**
     * Redirige a la página de propiedades con los filtros aplicados.
     * Se llama cuando se envía el formulario.
     */
    public function searchProperties(): void
    {
        // --- INICIO DE DEPURACIÓN ---
        Log::info('HeroSearch: searchProperties called.', [
            'operationType' => $this->operationType,
            'selectedPropertyType' => $this->selectedPropertyType,
            'locationSearch' => $this->locationSearch,
        ]);
        // --- FIN DE DEPURACIÓN ---

        // Validar que los tres campos obligatorios estén completos
        if (empty($this->operationType)) {
            $this->dispatch('notify', ['message' => 'Por favor, selecciona un tipo de operación (En Venta o En Renta).', 'type' => 'error']);
            Log::warning('HeroSearch: Validation failed - operationType is empty.');
            return;
        }

        if (empty($this->selectedPropertyType)) {
            $this->dispatch('notify', ['message' => 'Por favor, selecciona un tipo de propiedad.', 'type' => 'error']);
            Log::warning('HeroSearch: Validation failed - selectedPropertyType is empty.');
            return;
        }

        if (empty($this->locationSearch)) {
            $this->dispatch('notify', ['message' => 'Por favor, ingresa una ubicación.', 'type' => 'error']);
            Log::warning('HeroSearch: Validation failed - locationSearch is empty.');
            return;
        }

        // Si todos los campos están completos, construir los parámetros y redirigir
        $params = [
            'operacion' => $this->operationType,
            'tipo' => $this->selectedPropertyType,
            'ubicacion' => $this->locationSearch,
        ];

        Log::info('HeroSearch: Redirecting to properties.index with params.', ['params' => $params]);
        $this->redirect(route('properties.index', $params), navigate: true);
    }

    public function render()
    {
        return view('livewire.hero-search');
    }
}
