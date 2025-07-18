<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use Illuminate\Support\Collection;

class ShowProperties extends Component
{
    public Collection $properties;
    public bool $isLoading = false; // Nueva propiedad para manejar el estado de carga

    /**
     * Método de inicialización del componente.
     * Ahora recibe la colección de propiedades directamente.
     */
    public function mount(Collection $properties, bool $isLoading = false): void
    {
        $this->properties = $properties;
        $this->isLoading = $isLoading;
    }

    /**
     * Renderiza la vista del componente.
     * Simplemente pasa la colección de propiedades que ya tiene.
     */
    public function render()
    {
        return view('livewire.show-properties');
    }
}