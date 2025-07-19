<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On; // <--- ¡Asegúrate de importar este atributo!

class FavoritePropertiesIndex extends Component
{
    use WithPagination;

    public string $search = '';

    /**
     * Define los listeners de eventos.
     * Cuando se despacha 'favorite-updated', se llama al método 'render' (recarga el componente).
     */
    #[On('favorite-updated')] // <--- ¡NUEVO! Escucha el evento 'favorite-updated'
    public function render()
    {
        if (!Auth::check()) {
            $favoriteProperties = collect();
        } else {
            $user = Auth::user();

            $query = $user->favoriteProperties()
                          ->with(['propertyType', 'address', 'images', 'featureValues.feature'])
                          ->published()
                          ->latest();

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('address', function ($q2) {
                          $q2->where('street', 'like', '%' . $this->search . '%')
                             ->orWhere('neighborhood_name', 'like', '%' . $this->search . '%')
                             ->orWhere('municipality_name', 'like', '%' . $this->search . '%')
                             ->orWhere('state_name', 'like', '%' . $this->search . '%');
                      });
                });
            }

            $favoriteProperties = $query->paginate(12);
        }

        return view('livewire.favorite-properties-index', [
            'properties' => $favoriteProperties,
        ])->layout('layouts.app');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
