<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class FavoriteButtonDetail extends Component
{
    public Property $property;
    public bool $isFavorited;

    public function mount(Property $property): void
    {
        $this->property = $property;
        // Verificar correctamente si la propiedad está en favoritos
        $this->checkFavoriteStatus();
    }

    private function checkFavoriteStatus(): void
    {
        if (Auth::check()) {
            // Verificar si el usuario autenticado tiene esta propiedad en favoritos
            $this->isFavorited = Auth::user()
                ->favoriteProperties()
                ->where('property_id', $this->property->id)
                ->exists();
        } else {
            $this->isFavorited = false;
        }
    }

    public function toggleFavorite(): void
    {
        if (!Auth::check()) {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'Debes iniciar sesión para añadir propiedades a favoritos.',
            ]);
            return;
        }

        $user = Auth::user();

        if ($this->isFavorited) {
            // Remover de favoritos
            $user->favoriteProperties()->detach($this->property->id);
            $this->isFavorited = false;
            $this->dispatch('show-toast', [
                'type' => 'info',
                'message' => 'Propiedad eliminada de favoritos.',
            ]);
        } else {
            // Añadir a favoritos
            $user->favoriteProperties()->attach($this->property->id);
            $this->isFavorited = true;
            $this->dispatch('show-toast', [  // <-- CORREGIDO: era 'show-oast'
                'type' => 'success',
                'message' => 'Propiedad añadida a favoritos.',
            ]);
        }

        // Despacha un evento global después de la acción
        $this->dispatch('favorite-updated', [
            'propertyId' => $this->property->id,
            'isFavorited' => $this->isFavorited
        ]);
    }

    #[On('favorite-updated')]
    public function refreshFavoriteStatus($propertyId = null): void
    {
        // Si es la misma propiedad o no se especifica ID, actualizar el estado
        if (!$propertyId || $propertyId == $this->property->id) {
            $this->checkFavoriteStatus();
        }
    }

    public function render()
    {
        return view('livewire.favorite-button-detail');
    }
}
