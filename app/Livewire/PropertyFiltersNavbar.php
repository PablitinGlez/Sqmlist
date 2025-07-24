<?php

namespace App\Livewire;

use Livewire\Component;

class PropertyFiltersNavbar extends Component
{
    public $searchQuery = '';

    public $operationTypes = [
        'sale' => false,
        'rent' => false
    ];
    public $selectedOperationLabel = 'Tipo de Operación';

    public function updateOperationType($type)
    {
        if (!$this->operationTypes['sale'] && !$this->operationTypes['rent']) {
            $this->operationTypes[$type] = true;
        } else {
            $this->operationTypes[$type] = !$this->operationTypes[$type];
        }

        $this->updateOperationLabel();
    }

    private function updateOperationLabel()
    {
        if ($this->operationTypes['sale'] && $this->operationTypes['rent']) {
            $this->selectedOperationLabel = 'Venta y Renta';
        } elseif ($this->operationTypes['sale']) {
            $this->selectedOperationLabel = 'Venta';
        } elseif ($this->operationTypes['rent']) {
            $this->selectedOperationLabel = 'Renta';
        } else {
            $this->selectedOperationLabel = 'Tipo de Operación';
        }
    }

    public function render()
    {
        return view('livewire.property-filters-navbar');
    }
}
