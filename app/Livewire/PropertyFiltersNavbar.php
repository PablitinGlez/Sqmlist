<?php

namespace App\Livewire;

use Livewire\Component;

class PropertyFiltersNavbar extends Component
{
    // Variables para la barra de búsqueda (paso anterior)
    public $searchQuery = '';

    // Variables para el dropdown de Tipo de Operación
    public $operationTypes = [
        'sale' => false, // Venta
        'rent' => false  // Renta
    ];
    public $selectedOperationLabel = 'Tipo de Operación'; // Título del dropdown

    // Método para actualizar las selecciones
    public function updateOperationType($type)
    {
        // Forzar al menos una selección
        if (!$this->operationTypes['sale'] && !$this->operationTypes['rent']) {
            $this->operationTypes[$type] = true; // Activar la primera selección
        } else {
            $this->operationTypes[$type] = !$this->operationTypes[$type]; // Toggle
        }

        // Actualizar el título del dropdown
        $this->updateOperationLabel();
    }

    // Método para definir el título del dropdown
    private function updateOperationLabel()
    {
        if ($this->operationTypes['sale'] && $this->operationTypes['rent']) {
            $this->selectedOperationLabel = 'Venta y Renta';
        } elseif ($this->operationTypes['sale']) {
            $this->selectedOperationLabel = 'Venta';
        } elseif ($this->operationTypes['rent']) {
            $this->selectedOperationLabel = 'Renta';
        } else {
            $this->selectedOperationLabel = 'Tipo de Operación'; // Caso por defecto
        }
    }

    public function render()
    {
        return view('livewire.property-filters-navbar');
    }
}