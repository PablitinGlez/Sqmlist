<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class PropertyAddressForm extends Component
{
    // Input principal de búsqueda
    public ?string $searchAddress = '';

    // Campos de dirección detallados
    public ?string $street = null;
    public ?string $outdoor_number = null;
    public ?bool $no_external_number = false;
    public ?string $interior_number = null;
    public ?string $postal_code = null;

    // Nombres directos (como strings)
    public ?string $state_name = null;
    public ?string $municipality_name = null;
    public ?string $neighborhood_name = null;

    // Coordenadas y datos de Google
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?string $google_place_id = null;
    public ?array $google_address_components = null;

    // Configuración - Make this public so it's accessible in the view
    public ?string $googleMapsApiKey = null;

    // Sugerencias de autocompletado
    public array $suggestions = [];
    public bool $showSuggestions = false;

    // Estado del formulario
    public bool $showDetailedFields = false;
    public string $addressLevel = 'none'; // none, state, municipality, neighborhood, street

    public function mount(array $initialData = []): void
    {
        $this->googleMapsApiKey = config('services.google_maps.api_key');

        if (!empty($initialData)) {
            $this->fill($initialData);
            $this->determineAddressLevel();
            $this->showDetailedFields = true;
        }

        $this->emitAddressUpdate();
    }

    public function updatedSearchAddress(): void
    {
        if (strlen($this->searchAddress) >= 3) {
            $this->searchAddressWithGoogle();
        } else {
            $this->suggestions = [];
            $this->showSuggestions = false;
        }
    }

    public function searchAddressWithGoogle(): void
    {
        if (!$this->googleMapsApiKey || strlen($this->searchAddress) < 3) {
            return;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', [
                'input' => $this->searchAddress,
                'key' => $this->googleMapsApiKey,
                'language' => 'es',
                'components' => 'country:mx',
                'types' => 'geocode'
            ]);

            $data = $response->json();

            if ($data['status'] === 'OK' && isset($data['predictions'])) {
                $this->suggestions = collect($data['predictions'])->map(function ($prediction) {
                    return [
                        'place_id' => $prediction['place_id'],
                        'description' => $prediction['description'],
                        'main_text' => $prediction['structured_formatting']['main_text'] ?? $prediction['description'],
                        'secondary_text' => $prediction['structured_formatting']['secondary_text'] ?? '',
                        'types' => $prediction['types'] ?? []
                    ];
                })->toArray();

                $this->showSuggestions = true;
            }
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de Google Places:', ['error' => $e->getMessage()]);
        }
    }

    public function selectSuggestion(string $placeId, string $description): void
    {
        $this->searchAddress = $description;
        $this->suggestions = [];
        $this->showSuggestions = false;

        $this->getPlaceDetails($placeId);
    }

    public function getPlaceDetails(string $placeId): void
    {
        if (!$this->googleMapsApiKey) {
            return;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'key' => $this->googleMapsApiKey,
                'language' => 'es',
                'fields' => 'address_components,geometry,place_id,formatted_address,types'
            ]);

            $data = $response->json();

            if ($data['status'] === 'OK' && isset($data['result'])) {
                $this->processPlaceData($data['result']);
            }
        } catch (\Exception $e) {
            Log::error('Error obteniendo detalles del lugar:', ['error' => $e->getMessage()]);
        }
    }

    private function processPlaceData(array $place): void
    {
        // Resetear campos
        $this->resetAddressFields();

        // Asignar coordenadas y datos de Google
        $this->latitude = $place['geometry']['location']['lat'] ?? null;
        $this->longitude = $place['geometry']['location']['lng'] ?? null;
        $this->google_place_id = $place['place_id'] ?? null;
        $this->google_address_components = $place['address_components'] ?? [];

        // Procesar componentes de dirección
        $this->processAddressComponents($place['address_components'] ?? []);

        // Determinar nivel de dirección y mostrar campos
        $this->determineAddressLevel();
        $this->showDetailedFields = true;

        // Formatear el campo de búsqueda principal
        $this->formatSearchAddress();

        $this->emitAddressUpdate();
    }

    private function processAddressComponents(array $components): void
    {
        foreach ($components as $component) {
            $types = $component['types'];
            $longName = $component['long_name'];

            // Número de calle
            if (in_array('street_number', $types)) {
                $this->outdoor_number = $longName;
            }
            // Nombre de calle
            elseif (in_array('route', $types)) {
                $this->street = $longName;
            }
            // Código postal
            elseif (in_array('postal_code', $types)) {
                $this->postal_code = $longName;
            }
            // Estado
            elseif (in_array('administrative_area_level_1', $types)) {
                $this->state_name = $longName;
            }
            // Municipio/Ciudad
            elseif (in_array('locality', $types) || in_array('administrative_area_level_2', $types)) {
                $this->municipality_name = $longName;
            }
            // Colonia/Barrio
            elseif (in_array('sublocality_level_1', $types) || in_array('sublocality', $types) || in_array('neighborhood', $types)) {
                $this->neighborhood_name = $longName;
            }
        }

        // Si no hay número exterior, marcar como sin número
        if (empty($this->outdoor_number)) {
            $this->no_external_number = true;
        }
    }

    private function determineAddressLevel(): void
    {
        if ($this->street && ($this->outdoor_number || $this->no_external_number)) {
            $this->addressLevel = 'street';
        } elseif ($this->neighborhood_name) {
            $this->addressLevel = 'neighborhood';
        } elseif ($this->municipality_name) {
            $this->addressLevel = 'municipality';
        } elseif ($this->state_name) {
            $this->addressLevel = 'state';
        } else {
            $this->addressLevel = 'none';
        }
    }

    private function formatSearchAddress(): void
    {
        $parts = [];

        if ($this->postal_code) {
            $parts[] = $this->postal_code;
        }

        if ($this->municipality_name) {
            $parts[] = $this->municipality_name;
        }

        if ($this->state_name) {
            $parts[] = $this->state_name;
        }

        if (!empty($parts)) {
            $this->searchAddress = implode(' ', $parts);
        }
    }

    private function resetAddressFields(): void
    {
        $this->street = null;
        $this->outdoor_number = null;
        $this->interior_number = null;
        $this->no_external_number = false;
        $this->postal_code = null;
        $this->state_name = null;
        $this->municipality_name = null;
        $this->neighborhood_name = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->google_place_id = null;
        $this->google_address_components = null;
        $this->addressLevel = 'none';
    }

    public function hideSuggestions(): void
    {
        $this->showSuggestions = false;
    }

    // Métodos para actualizar campos individuales
    public function updatedStreet(): void
    {
        $this->emitAddressUpdate();
    }
    public function updatedOutdoorNumber(): void
    {
        $this->emitAddressUpdate();
    }
    public function updatedNoExternalNumber(bool $value): void
    {
        if ($value) {
            $this->outdoor_number = null;
        }
        $this->emitAddressUpdate();
    }
    public function updatedInteriorNumber(): void
    {
        $this->emitAddressUpdate();
    }
    public function updatedPostalCode(): void
    {
        $this->emitAddressUpdate();
    }
    public function updatedStateName(): void
    {
        $this->emitAddressUpdate();
    }
    public function updatedMunicipalityName(): void
    {
        $this->emitAddressUpdate();
    }
    public function updatedNeighborhoodName(): void
    {
        $this->emitAddressUpdate();
    }

    private function emitAddressUpdate(): void
    {
        $dataToEmit = [
            'street' => $this->street,
            'outdoor_number' => $this->outdoor_number,
            'no_external_number' => $this->no_external_number,
            'interior_number' => $this->interior_number,
            'postal_code' => $this->postal_code,
            'state_name' => $this->state_name,
            'municipality_name' => $this->municipality_name,
            'neighborhood_name' => $this->neighborhood_name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'google_place_id' => $this->google_place_id,
            'google_address_components' => $this->google_address_components,
        ];

        $this->dispatch('propertyAddressUpdated', $dataToEmit);
    }

    public function render()
    {
        // Remove the array parameter - Livewire will automatically make public properties available
        return view('livewire.property-address-form');
    }
}
