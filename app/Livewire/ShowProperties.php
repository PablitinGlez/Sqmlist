<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;

class ShowProperties extends Component
{
    public Collection $properties;
    public bool $isLoading = false;

    public function mount(Collection $properties, bool $isLoading = false): void
    {
        $this->properties = $properties;
        $this->isLoading = $isLoading;
    }

    public function render()
    {
        return view('livewire.show-properties');
    }
}
