<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Colonia;
use Livewire\Attributes\On;

class AddressAutocomplete extends Component
{
    public string $search = '';
    public array $suggestions = [];
    public array $selectedAddressData = [
        'street' => '',
        'outdoor_number' => '',
        'interior_number' => '',
        'postal_code' => '',
        'state_name' => '',
        'municipality_name' => '',
        'neighborhood_name' => '',
        'latitude' => null,
        'longitude' => null,
        'google_place_id' => null,
        'google_address_components' => null,
        'no_external_number' => false,
        'no_interior_number' => false,
    ];


    public ?int $selected_state_id = null;
    public ?int $selected_municipality_id = null;
    public ?int $selected_colonia_id = null;

    public bool $is_outdoor_number_sn = false;
    public bool $is_interior_number_sn = false;

    public $states = [];
    public $municipalities = [];
    public $colonias = [];

    public bool $show_municipality_select = false;
    public bool $show_colonia_select = false;
    public bool $show_map = false;

    public string $data_source = 'none';




    #[On('mapLocationUpdated')]
    public function mapLocationUpdated(float $lat, float $lng): void
    {
        $this->selectedAddressData['latitude'] = $lat;
        $this->selectedAddressData['longitude'] = $lng;
        $this->notifyAddressChange();
    }

    #[On('setInitialAddressData')]
    public function setInitialAddressData(?array $initialData = null): void
    {
        if ($initialData) {
            $this->selectedAddressData = array_merge($this->selectedAddressData, $initialData);

            // Asegurar que los booleanos se manejen correctamente si vienen como string o int
            $this->selectedAddressData['no_external_number'] = (bool)($this->selectedAddressData['no_external_number'] ?? false);
            $this->selectedAddressData['no_interior_number'] = (bool)($this->selectedAddressData['no_interior_number'] ?? false);

            // Sincronizar los checkboxes 'S/N' con los datos iniciales
            $this->is_outdoor_number_sn = ($this->selectedAddressData['outdoor_number'] === 'S/N' || $this->selectedAddressData['no_external_number']);
            $this->is_interior_number_sn = ($this->selectedAddressData['interior_number'] === 'S/N' || $this->selectedAddressData['no_interior_number']);

            $this->buildSearchFromData();
            $this->initializeFromData();

            if ($this->selectedAddressData['latitude'] && $this->selectedAddressData['longitude']) {
                $this->show_map = true;
                $this->dispatch('updateMap', [
                    'lat' => $this->selectedAddressData['latitude'],
                    'lng' => $this->selectedAddressData['longitude']
                ]);
            }
        }
    }

    public function mount(): void
    {
        $this->states = State::orderBy('name')->get();
    }

    public function updatedSelectedAddressData(): void
    {
        $this->notifyAddressChange();
    }

    public function updatedSelectedAddressDataStreet(): void
    {
        $this->notifyAddressChange();
    }

    public function updatedSelectedAddressDataOutdoorNumber(): void
    {

        if (!empty($this->selectedAddressData['outdoor_number']) && $this->selectedAddressData['outdoor_number'] !== 'S/N' && $this->is_outdoor_number_sn) {
            $this->is_outdoor_number_sn = false;
            $this->selectedAddressData['no_external_number'] = false;
        }
        $this->notifyAddressChange();
    }

    public function updatedSelectedAddressDataInteriorNumber(): void
    {

        if (!empty($this->selectedAddressData['interior_number']) && $this->selectedAddressData['interior_number'] !== 'S/N' && $this->is_interior_number_sn) {
            $this->is_interior_number_sn = false;
            $this->selectedAddressData['no_interior_number'] = false;
        }
        $this->notifyAddressChange();
    }

    private function buildSearchFromData(): void
    {
        $addressParts = array_filter([
            $this->selectedAddressData['street'],
            $this->selectedAddressData['outdoor_number'],
            $this->selectedAddressData['interior_number'],
            $this->selectedAddressData['neighborhood_name'],
            $this->selectedAddressData['municipality_name'],
            $this->selectedAddressData['state_name'],
            $this->selectedAddressData['postal_code'] ? 'C.P. ' . $this->selectedAddressData['postal_code'] : null
        ]);
        $this->search = implode(', ', $addressParts);
    }

    private function initializeFromData(): void
    {
        if ($this->selectedAddressData['state_name']) {
            $state = State::where('name', $this->selectedAddressData['state_name'])->first();
            if ($state) {
                $this->selected_state_id = $state->id;
                $this->loadMunicipalities();
                $this->show_municipality_select = true;

                if ($this->selectedAddressData['municipality_name']) {
                    $municipality = Municipality::where('name', $this->selectedAddressData['municipality_name'])
                        ->where('state_id', $state->id)->first();
                    if ($municipality) {
                        $this->selected_municipality_id = $municipality->id;
                        $this->loadColonias();
                        $this->show_colonia_select = true;

                        if ($this->selectedAddressData['neighborhood_name']) {
                            $colonia = Colonia::where('name', $this->selectedAddressData['neighborhood_name'])
                                ->where('municipality_id', $municipality->id)->first();
                            if ($colonia) {
                                $this->selected_colonia_id = $colonia->id;
                            }
                        }
                    }
                }
            }
        }
    }

    public function updatedSearch(): void
    {
        $this->suggestions = [];
        $this->resetAddressData();
        $this->show_map = false;
        $this->dispatch('resetMap');

        if (strlen($this->search) < 3) {
            return;
        }

        $apiKey = Config::get('services.google_maps.api_key');
        if (!$apiKey) {
            $this->addError('api_key', 'La clave de la API de Google Maps no está configurada.');
            return;
        }

        $apiUrl = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';
        $queryParams = [
            'input' => $this->search,
            'key' => $apiKey,
            'language' => 'es',
            'components' => 'country:mx',
        ];

        $response = Http::get($apiUrl, $queryParams);
        $data = $response->json();

        if ($response->successful() && isset($data['predictions'])) {
            $this->suggestions = $data['predictions'];
        } else {
            $this->addError('search', 'No se pudieron obtener sugerencias de dirección. Inténtalo de nuevo.');
        }
    }

    public function selectSuggestion(string $placeId, string $description): void
    {
        $this->search = $description;
        $this->suggestions = [];
        $this->data_source = 'google';

        $apiKey = Config::get('services.google_maps.api_key');
        if (!$apiKey) {
            $this->addError('api_key', 'La clave de la API de Google Maps no está configurada.');
            return;
        }

        $apiUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $queryParams = [
            'place_id' => $placeId,
            'key' => $apiKey,
            'language' => 'es',
        ];

        $response = Http::get($apiUrl, $queryParams);
        $data = $response->json();

        if ($response->successful() && isset($data['results'][0])) {
            $result = $data['results'][0];

            $this->resetAllDbSelects();

            $this->selectedAddressData['latitude'] = $result['geometry']['location']['lat'];
            $this->selectedAddressData['longitude'] = $result['geometry']['location']['lng'];
            $this->selectedAddressData['google_place_id'] = $placeId;
            $this->selectedAddressData['google_address_components'] = $result['address_components'];

            $googleComponents = $this->parseGoogleComponents($result['address_components']);

            $this->selectedAddressData['street'] = $googleComponents['street'] ?? '';
            $this->selectedAddressData['outdoor_number'] = $googleComponents['outdoor_number'] ?? '';
            $this->selectedAddressData['interior_number'] = $googleComponents['interior_number'] ?? '';

            $this->autoCompleteWithLocalDatabase($googleComponents);

            // Ajustar los checkboxes S/N después de intentar autocompletar con DB o Google
            $this->is_outdoor_number_sn = (empty($this->selectedAddressData['outdoor_number']) && !empty($this->selectedAddressData['street']));
            $this->is_interior_number_sn = (empty($this->selectedAddressData['interior_number']) && !empty($this->selectedAddressData['street']));

            $this->selectedAddressData['no_external_number'] = $this->is_outdoor_number_sn;
            $this->selectedAddressData['no_interior_number'] = $this->is_interior_number_sn;

            $this->checkIfShowMap();

            if ($this->show_map && $this->selectedAddressData['latitude'] !== null && $this->selectedAddressData['longitude'] !== null) {
                $this->dispatch('updateMap', [
                    'lat' => $this->selectedAddressData['latitude'],
                    'lng' => $this->selectedAddressData['longitude']
                ]);
            }

            $this->notifyAddressChange();
        } else {
            $this->addError('search', 'No se pudieron obtener los detalles de la dirección. Inténtalo de nuevo.');
        }
    }

    public function updatedIsOutdoorNumberSn(): void
    {
        if ($this->is_outdoor_number_sn) {
            $this->selectedAddressData['outdoor_number'] = 'S/N';
            $this->selectedAddressData['no_external_number'] = true;
        } else {
            // Solo limpia si el valor actual es 'S/N' (establecido por el checkbox)
            if ($this->selectedAddressData['outdoor_number'] === 'S/N') {
                $this->selectedAddressData['outdoor_number'] = '';
            }
            $this->selectedAddressData['no_external_number'] = false;
        }
        $this->notifyAddressChange();
    }

    public function updatedIsInteriorNumberSn(): void
    {
        if ($this->is_interior_number_sn) {
            $this->selectedAddressData['interior_number'] = 'S/N';
            $this->selectedAddressData['no_interior_number'] = true;
        } else {
            // Solo limpia si el valor actual es 'S/N' (establecido por el checkbox)
            if ($this->selectedAddressData['interior_number'] === 'S/N') {
                $this->selectedAddressData['interior_number'] = '';
            }
            $this->selectedAddressData['no_interior_number'] = false;
        }
        $this->notifyAddressChange();
    }

    protected function parseGoogleComponents(array $components): array
    {
        $parsed = [
            'street' => '',
            'outdoor_number' => '',
            'interior_number' => '',
            'postal_code' => '',
            'state_name' => '',
            'municipality_name' => '',
            'neighborhood_name' => '',
        ];

        foreach ($components as $component) {
            $types = $component['types'];
            $longName = $component['long_name'];

            if (in_array('street_number', $types)) {
                $parsed['outdoor_number'] = $longName;
            } elseif (in_array('route', $types)) {
                $parsed['street'] = $longName;
            } elseif (in_array('sublocality_level_1', $types) || in_array('neighborhood', $types) || in_array('sublocality', $types)) {
                $parsed['neighborhood_name'] = $longName;
            } elseif (in_array('postal_code', $types)) {
                $parsed['postal_code'] = $longName;
            } elseif (in_array('locality', $types)) {
                $parsed['municipality_name'] = $longName;
            } elseif (in_array('administrative_area_level_2', $types)) {
                $parsed['municipality_name'] = $longName;
            } elseif (in_array('administrative_area_level_1', $types)) {
                $parsed['state_name'] = $longName;
            }
        }
        return $parsed;
    }

    private function autoCompleteWithLocalDatabase(array $googleComponents): void
    {
        $normalizedOriginalSearch = $this->normalizeString($this->search);

        if (!empty($googleComponents['neighborhood_name']) && !empty($googleComponents['municipality_name']) && !empty($googleComponents['state_name'])) {
            $foundColonia = $this->findColoniaByName(
                $googleComponents['neighborhood_name'],
                $googleComponents['municipality_name'],
                $googleComponents['state_name']
            );
            if ($foundColonia) {
                $this->autoCompleteFromColonia($foundColonia);
                $this->data_source = 'database';
                return;
            }
        }

        if (!empty($googleComponents['postal_code'])) {
            $coloniasByCp = Colonia::where('postal_code', $googleComponents['postal_code'])->get();

            if ($coloniasByCp->isNotEmpty()) {
                $bestMatchColonia = null;
                $maxMatchScore = -1;

                foreach ($coloniasByCp as $colonia) {
                    $currentScore = 0;

                    $normalizedColoniaNameDB = $this->normalizeString($colonia->name);
                    $normalizedMunicipalityNameDB = $this->normalizeString($colonia->municipality->name);
                    $normalizedStateNameDB = $this->normalizeString($colonia->municipality->state->name);

                    if (!empty($googleComponents['neighborhood_name']) && $normalizedColoniaNameDB === $this->normalizeString($googleComponents['neighborhood_name'])) {
                        $currentScore += 3;
                    }

                    if (!empty($googleComponents['municipality_name']) && $normalizedMunicipalityNameDB === $this->normalizeString($googleComponents['municipality_name'])) {
                        $currentScore += 2;
                    }

                    if (!empty($googleComponents['state_name']) && str_starts_with($normalizedStateNameDB, $this->normalizeString($googleComponents['state_name']))) {
                        $currentScore += 1;
                    }

                    if (empty($googleComponents['neighborhood_name']) && str_contains($normalizedOriginalSearch, $normalizedColoniaNameDB)) {
                        $currentScore += 4;
                    }

                    if ($currentScore > $maxMatchScore) {
                        $maxMatchScore = $currentScore;
                        $bestMatchColonia = $colonia;
                    }
                }

                if ($bestMatchColonia) {
                    $this->autoCompleteFromColonia($bestMatchColonia);
                    $this->data_source = 'database';
                    return;
                }
            }
        }

        if (!empty($googleComponents['municipality_name']) && !empty($googleComponents['state_name'])) {
            $foundState = $this->findStateByName($googleComponents['state_name']);
            if ($foundState) {
                $foundMunicipality = $this->findMunicipalityByName($googleComponents['municipality_name'], $foundState->id);
                if ($foundMunicipality) {
                    $this->autoCompleteFromMunicipality($foundMunicipality);
                    $this->data_source = 'database';
                    return;
                }
            }
        }

        if (!empty($googleComponents['state_name'])) {
            $foundState = $this->findStateByName($googleComponents['state_name']);
            if ($foundState) {
                $this->autoCompleteFromState($foundState);
                $this->data_source = 'database';
                return;
            }
        }

        $this->selectedAddressData['state_name'] = $googleComponents['state_name'] ?? '';
        $this->selectedAddressData['municipality_name'] = $googleComponents['municipality_name'] ?? '';
        $this->selectedAddressData['neighborhood_name'] = $googleComponents['neighborhood_name'] ?? '';
        $this->selectedAddressData['postal_code'] = $googleComponents['postal_code'] ?? '';
        $this->buildSearchFromData();
        $this->data_source = 'google';
    }

    private function autoCompleteFromColonia(Colonia $colonia): void
    {
        $this->selected_colonia_id = $colonia->id;
        $this->selectedAddressData['neighborhood_name'] = $colonia->name;
        $this->selectedAddressData['postal_code'] = $colonia->postal_code;

        $this->selected_municipality_id = $colonia->municipality_id;
        $this->selectedAddressData['municipality_name'] = $colonia->municipality->name;
        $this->loadColonias();
        $this->show_colonia_select = true;

        $this->selected_state_id = $colonia->municipality->state_id;
        $this->selectedAddressData['state_name'] = $colonia->municipality->state->name;
        $this->loadMunicipalities();
        $this->show_municipality_select = true;

        $this->buildSearchFromData();

        // Asegurarse de que los campos de número exterior/interior estén vacíos y los checkboxes desmarcados
        $this->is_outdoor_number_sn = false;
        $this->selectedAddressData['no_external_number'] = false;
        $this->selectedAddressData['outdoor_number'] = '';

        $this->is_interior_number_sn = false;
        $this->selectedAddressData['no_interior_number'] = false;
        $this->selectedAddressData['interior_number'] = '';

        $this->selectedAddressData['street'] = '';
    }

    private function autoCompleteFromMunicipality(Municipality $municipality): void
    {
        $this->selected_municipality_id = $municipality->id;
        $this->selectedAddressData['municipality_name'] = $municipality->name;
        $this->loadColonias();
        $this->show_colonia_select = true;

        $this->selected_state_id = $municipality->state_id;
        $this->selectedAddressData['state_name'] = $municipality->state->name;
        $this->loadMunicipalities();
        $this->show_municipality_select = true;

        $this->selected_colonia_id = null;
        $this->selectedAddressData['neighborhood_name'] = '';
        $this->selectedAddressData['postal_code'] = '';

        $this->buildSearchFromData();

        $this->is_outdoor_number_sn = false;
        $this->selectedAddressData['no_external_number'] = false;
        $this->selectedAddressData['outdoor_number'] = '';

        $this->is_interior_number_sn = false;
        $this->selectedAddressData['no_interior_number'] = false;
        $this->selectedAddressData['interior_number'] = '';

        $this->selectedAddressData['street'] = '';
    }

    private function autoCompleteFromState(State $state): void
    {
        $this->selected_state_id = $state->id;
        $this->selectedAddressData['state_name'] = $state->name;
        $this->loadMunicipalities();
        $this->show_municipality_select = true;

        $this->selected_municipality_id = null;
        $this->selectedAddressData['municipality_name'] = '';
        $this->selected_colonia_id = null;
        $this->selectedAddressData['neighborhood_name'] = '';
        $this->selectedAddressData['postal_code'] = '';
        $this->colonias = [];
        $this->show_colonia_select = false;

        $this->buildSearchFromData();

        $this->is_outdoor_number_sn = false;
        $this->selectedAddressData['no_external_number'] = false;
        $this->selectedAddressData['outdoor_number'] = '';

        $this->is_interior_number_sn = false;
        $this->selectedAddressData['no_interior_number'] = false;
        $this->selectedAddressData['interior_number'] = '';

        $this->selectedAddressData['street'] = '';
    }

    private function findColoniaByName(string $coloniaName, ?string $municipalityName = null, ?string $stateName = null): ?Colonia
    {
        $normalizedColoniaName = $this->normalizeString($coloniaName);

        $query = Colonia::query();

        if ($municipalityName) {
            $normalizedMunicipalityName = $this->normalizeString($municipalityName);
            $query->whereHas('municipality', function ($q2) use ($normalizedMunicipalityName) {
                $q2->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, "á", "a"), "é", "e"), "í", "i"), "ó", "o"), "ú", "u"), "ñ", "n")) = ?', [$normalizedMunicipalityName]);
            });
        }

        if ($stateName) {
            $normalizedStateName = $this->normalizeString($stateName);
            $query->whereHas('municipality.state', function ($q2) use ($normalizedStateName) {
                $q2->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, "á", "a"), "é", "e"), "í", "i"), "ó", "o"), "ú", "u"), "ñ", "n")) LIKE ?', [$normalizedStateName . '%']);
            });
        }

        $query->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, "á", "a"), "é", "e"), "í", "i"), "ó", "o"), "ú", "u"), "ñ", "n")) = ?', [$normalizedColoniaName]);

        $result = $query->first();
        return $result;
    }

    private function findStateByName(string $stateName): ?State
    {
        $normalizedStateName = $this->normalizeString($stateName);
        $result = State::whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, "á", "a"), "é", "e"), "í", "i"), "ó", "o"), "ú", "u"), "ñ", "n")) LIKE ?', [$normalizedStateName . '%'])->first();
        return $result;
    }

    private function findMunicipalityByName(string $municipalityName, int $stateId): ?Municipality
    {
        $normalizedMunicipalityName = $this->normalizeString($municipalityName);
        $result = Municipality::where('state_id', $stateId)
            ->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, "á", "a"), "é", "e"), "í", "i"), "ó", "o"), "ú", "u"), "ñ", "n")) = ?', [$normalizedMunicipalityName])
            ->first();
        return $result;
    }

    protected function normalizeString(string $string): string
    {
        $string = mb_strtolower($string, 'UTF-8');
        $string = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'N'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'U', 'N'],
            $string
        );
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        $normalized = trim($string);
        return $normalized;
    }

    public function updatedSelectedStateId(): void
    {
        $this->data_source = 'database';
        $this->resetMunicipalityAndBelow();

        if ($this->selected_state_id) {
            $state = State::find($this->selected_state_id);
            $this->selectedAddressData['state_name'] = $state->name;
            $this->loadMunicipalities();
            $this->show_municipality_select = true;
        } else {
            $this->selectedAddressData['state_name'] = '';
        }
        $this->buildSearchFromData();
        $this->updateMapFromFullAddress();
        $this->notifyAddressChange();
    }

    public function updatedSelectedMunicipalityId(): void
    {
        $this->data_source = 'database';
        $this->resetColoniaAndBelow();

        if ($this->selected_municipality_id) {
            $municipality = Municipality::find($this->selected_municipality_id);
            $this->selectedAddressData['municipality_name'] = $municipality->name;
            $this->loadColonias();
            $this->show_colonia_select = true;
        } else {
            $this->selectedAddressData['municipality_name'] = '';
        }
        $this->buildSearchFromData();
        $this->updateMapFromFullAddress();
        $this->notifyAddressChange();
    }

    public function updatedSelectedColoniaId(): void
    {
        $this->data_source = 'database';

        if ($this->selected_colonia_id) {
            $colonia = Colonia::find($this->selected_colonia_id);
            $this->selectedAddressData['neighborhood_name'] = $colonia->name;
            $this->selectedAddressData['postal_code'] = $colonia->postal_code;
            $this->buildSearchFromData();
            $this->updateMapFromFullAddress();
        } else {
            $this->selectedAddressData['neighborhood_name'] = '';
            $this->selectedAddressData['postal_code'] = '';
            $this->show_map = false;
            $this->dispatch('resetMap');
        }
        $this->notifyAddressChange();
    }

    private function loadMunicipalities(): void
    {
        $this->municipalities = [];
        if ($this->selected_state_id) {
            $this->municipalities = Municipality::where('state_id', $this->selected_state_id)
                ->orderBy('name')
                ->get();
        }
    }

    private function loadColonias(): void
    {
        $this->colonias = [];
        if ($this->selected_municipality_id) {
            $this->colonias = Colonia::where('municipality_id', $this->selected_municipality_id)
                ->orderBy('name')
                ->get();
        }
    }

    private function resetMunicipalityAndBelow(): void
    {
        $this->selected_municipality_id = null;
        $this->municipalities = [];
        $this->show_municipality_select = false;
        $this->selectedAddressData['municipality_name'] = '';
        $this->resetColoniaAndBelow();
    }

    private function resetColoniaAndBelow(): void
    {
        $this->selected_colonia_id = null;
        $this->colonias = [];
        $this->show_colonia_select = false;
        $this->selectedAddressData['neighborhood_name'] = '';
        $this->selectedAddressData['postal_code'] = '';
        $this->show_map = false;
        $this->dispatch('resetMap');
    }

    private function resetAllDbSelects(): void
    {
        $this->selected_state_id = null;
        $this->selectedAddressData['state_name'] = '';
        $this->resetMunicipalityAndBelow();
    }

    private function resetAddressData(): void
    {
        $this->selectedAddressData = [
            'street' => '',
            'outdoor_number' => '',
            'interior_number' => '',
            'postal_code' => '',
            'state_name' => '',
            'municipality_name' => '',
            'neighborhood_name' => '',
            'latitude' => null,
            'longitude' => null,
            'google_place_id' => null,
            'google_address_components' => null,
            'no_external_number' => false,
            'no_interior_number' => false,
        ];
        $this->resetAllDbSelects();
        $this->is_outdoor_number_sn = false;
        $this->is_interior_number_sn = false;
    }

    private function checkIfShowMap(): void
    {
        $this->show_map = !empty($this->selectedAddressData['state_name']) &&
            !empty($this->selectedAddressData['municipality_name']) &&
            !empty($this->selectedAddressData['neighborhood_name']) &&
            !empty($this->selectedAddressData['postal_code']);

        if ($this->show_map && (is_null($this->selectedAddressData['latitude']) || is_null($this->selectedAddressData['longitude']))) {
            $this->updateMapFromFullAddress();
        } elseif (!$this->show_map) {
            $this->dispatch('resetMap');
        }
    }

    protected function updateMapFromFullAddress(): void
    {
        if (
            $this->selectedAddressData['state_name'] &&
            $this->selectedAddressData['municipality_name'] &&
            $this->selectedAddressData['neighborhood_name'] &&
            $this->selectedAddressData['postal_code']
        ) {

            $fullAddress = implode(', ', array_filter([
                $this->selectedAddressData['neighborhood_name'],
                $this->selectedAddressData['municipality_name'],
                $this->selectedAddressData['state_name'],
                $this->selectedAddressData['postal_code'],
                'México'
            ]));

            $apiKey = Config::get('services.google_maps.api_key');
            if (!$apiKey) {
                return;
            }

            $apiUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
            $queryParams = [
                'address' => $fullAddress,
                'key' => $apiKey,
                'language' => 'es',
                'components' => 'country:mx',
            ];

            $response = Http::get($apiUrl, $queryParams);
            $data = $response->json();

            if ($response->successful() && isset($data['results'][0])) {
                $result = $data['results'][0];
                $this->selectedAddressData['latitude'] = $result['geometry']['location']['lat'];
                $this->selectedAddressData['longitude'] = $result['geometry']['location']['lng'];
                $this->show_map = true;
                $this->dispatch('updateMap', [
                    'lat' => $this->selectedAddressData['latitude'],
                    'lng' => $this->selectedAddressData['longitude']
                ]);
            } else {
                $this->show_map = false;
            }
        } else {
            $this->show_map = false;
        }
    }

    public function clearForm(): void
    {
        $this->search = '';
        $this->suggestions = [];
        $this->selectedAddressData = [
            'street' => '',
            'outdoor_number' => '',
            'interior_number' => '',
            'postal_code' => '',
            'state_name' => '',
            'municipality_name' => '',
            'neighborhood_name' => '',
            'latitude' => null,
            'longitude' => null,
            'google_place_id' => null,
            'google_address_components' => null,
            'no_external_number' => false,
            'no_interior_number' => false,
        ];
        $this->selected_state_id = null;
        $this->selected_municipality_id = null;
        $this->selected_colonia_id = null;
        $this->municipalities = [];
        $this->colonias = [];
        $this->show_municipality_select = false;
        $this->show_colonia_select = false;
        $this->show_map = false;
        $this->data_source = 'none';
        $this->is_outdoor_number_sn = false;
        $this->is_interior_number_sn = false;
        $this->dispatch('resetMap');
        $this->notifyAddressChange();
    }

    private function notifyAddressChange(): void
    {
        $this->dispatch('addressSelected', $this->selectedAddressData);
    }

    public function render()
    {
        return view('livewire.address-autocomplete');
    }
}
