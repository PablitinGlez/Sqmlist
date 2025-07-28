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
        $this->checkFavoriteStatus();
    }

    private function checkFavoriteStatus(): void
    {
        if (Auth::check()) {
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

        $this->dispatch('favorite-updated', [
            'propertyId' => $this->property->id,
            'isFavorited' => $this->isFavorited
        ]);
    }

    #[On('favorite-updated')]
    public function refreshFavoriteStatus($propertyId = null): void
    {
        if (!$propertyId || $propertyId == $this->property->id) {
            $this->checkFavoriteStatus();
        }
    }

    public function render()
    {
        return view('livewire.favorite-button-detail');
    }
}
