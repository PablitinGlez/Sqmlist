<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On; // Importa el atributo On

class FavoriteButton extends Component
{
    public Property $property;
    public bool $isFavorited;

    public function mount(Property $property): void
    {
        $this->property = $property;
        $this->isFavorited = Auth::check() && Auth::user()->hasFavorited($this->property);
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

        if ($user->hasFavorited($this->property)) {
            $user->favoriteProperties()->detach($this->property->id);
            $this->isFavorited = false;
            $this->dispatch('show-toast', [
                'type' => 'info',
                'message' => 'Propiedad eliminada de favoritos.',
            ]);
        } else {
            $user->favoriteProperties()->attach($this->property->id);
            $this->isFavorited = true;
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Propiedad añadida a favoritos.',
            ]);
        }
        
        // --- ¡NUEVO! Despacha un evento global después de la acción ---
        // Esto notificará a otros componentes Livewire que la lista de favoritos ha cambiado.
        $this->dispatch('favorite-updated'); 
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}
