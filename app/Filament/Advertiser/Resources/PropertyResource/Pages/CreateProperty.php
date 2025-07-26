<?php

namespace App\Filament\Advertiser\Resources\PropertyResource\Pages;

use App\Filament\Advertiser\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Feature;
use App\Models\PropertyFeatureValue;
use App\Models\PropertyImage;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Address;
use Illuminate\Support\Facades\Log;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    // Solo necesitamos esta propiedad personalizada
    protected array $featureValuesToSave = [];

    // Sobrescribir la propiedad data para asegurar inicialización
    public ?array $data = [];

    public function mount(): void
    {
        // Primero inicializar data antes del parent::mount()
        $this->data = [
            'feature_values' => [],
            'description' => '',
            'images' => [], // MUY IMPORTANTE - debe estar presente
            'property_images' => [],
            'title' => '',
            'price' => null,
            'operation_type' => null,
            'property_type_id' => null,
            'address_data' => [],
        ];

        parent::mount();

        // Llenar el formulario después del mount del padre
        $this->form->fill($this->data);

        Log::info('CreateProperty: Mount completed with data:', $this->data);
    }

    #[On('updateFormData')]
    public function handleFormDataUpdate(array $data): void
    {
        // Asegurar que 'images' siempre esté presente
        if (!isset($data['images'])) {
            $data['images'] = [];
        }

        // Asegurar que otros campos importantes estén presentes
        $requiredFields = ['feature_values', 'description', 'property_images', 'title', 'address_data'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $currentData = $this->form->getState();
                $data[$field] = $currentData[$field] ?? (in_array($field, ['feature_values', 'property_images', 'address_data']) ? [] : '');
            }
        }

        $this->form->fill($data);

        Log::info('CreateProperty: Form data updated:', $data);
    }

    #[On('addressSelected')]
    public function handleAddressSelected(array $addressData): void
    {
        $this->form->fill([
            'address_data' => $addressData,
        ]);

        Log::info('CreateProperty: Address selected:', $addressData);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending_review';
        $data['draft_expires_at'] = now()->addDays(7);

        $this->featureValuesToSave = [];

        if (isset($data['feature_values']) && is_array($data['feature_values'])) {
            $propertyTypeId = $data['property_type_id'] ?? null;

            if ($propertyTypeId) {
                $features = Feature::whereHas('propertyTypes', function ($query) use ($propertyTypeId) {
                    $query->where('property_types.id', $propertyTypeId);
                })->get()->keyBy('slug');

                foreach ($data['feature_values'] as $slug => $value) {
                    if ($features->has($slug)) {
                        $feature = $features[$slug];

                        if ($feature->data_type === 'boolean') {
                            $processedValue = (bool) $value;
                        } elseif ($feature->data_type === 'array' || $feature->data_type === 'json') {
                            $processedValue = is_array($value) ? json_encode($value) : json_encode([$value]);
                        } else {
                            $processedValue = $value === '' ? null : $value;
                        }

                        $this->featureValuesToSave[] = [
                            'feature_id' => $feature->id,
                            'value' => $processedValue,
                        ];
                    }
                }
            }
            unset($data['feature_values']);
        }

        Log::info('CreateProperty: Data before create:', $data);
        return $data;
    }

    protected function afterCreate(): void
    {
        // Guardar feature values
        foreach ($this->featureValuesToSave as $featureValueData) {
            $this->record->featureValues()->create($featureValueData);
        }

        $formData = $this->form->getState();

        Log::info('CreateProperty: afterCreate - Intentando guardar dirección para propiedad ID: ' . $this->record->id);
        Log::info('CreateProperty: address_data recibida: ', $formData['address_data'] ?? []);

        // Guardar dirección
        if (isset($formData['address_data']) && is_array($formData['address_data']) && !empty($formData['address_data'])) {
            try {
                $address = $this->record->address()->create($formData['address_data']);
                Log::info('CreateProperty: Dirección guardada exitosamente. ID de dirección: ' . $address->id);

                // Regenerar slug
                $this->record->fresh()->regenerateSlug();
                Log::info('CreateProperty: Slug regenerado después de crear dirección. Nuevo slug: ' . $this->record->fresh()->slug);
            } catch (\Exception $e) {
                Log::error('CreateProperty: Error al guardar la dirección: ' . $e->getMessage(), ['exception' => $e]);
            }
        } else {
            Log::warning('CreateProperty: address_data no está presente o está vacío en el formulario.');
        }

        // Guardar imágenes
        $uploadedImagePaths = $formData['images'] ?? [];
        Log::info('CreateProperty: Procesando imágenes:', $uploadedImagePaths);

        if (!empty($uploadedImagePaths) && is_array($uploadedImagePaths)) {
            $order = 1;
            foreach ($uploadedImagePaths as $imagePath) {
                try {
                    PropertyImage::create([
                        'property_id' => $this->record->id,
                        'path' => $imagePath,
                        'order' => $order,
                        'is_featured' => $order === 1,
                    ]);
                    Log::info('CreateProperty: Imagen guardada:', ['path' => $imagePath, 'order' => $order]);
                    $order++;
                } catch (\Exception $e) {
                    Log::error('CreateProperty: Error al guardar imagen: ' . $e->getMessage(), ['path' => $imagePath]);
                }
            }
        } else {
            Log::warning('CreateProperty: No se encontraron imágenes para guardar.');
        }

        \Filament\Notifications\Notification::make()
            ->title('Propiedad enviada a revisión exitosamente.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return PropertyResource::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function hasUnsavedChanges(): bool
    {
        return false;
    }
}
