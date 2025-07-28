<?php

namespace App\Filament\Admin\Resources\PropertyTypeResource\Pages;

use App\Filament\Admin\Resources\PropertyTypeResource;
use App\Models\PropertyType;
use App\Models\FeatureSection;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePropertyType extends CreateRecord
{
    protected static string $resource = PropertyTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $featureSections = $data['feature_sections'] ?? [];
        $selectedFeatures = [];

        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'feature_') && $value === true) {
                $featureId = str_replace('feature_', '', $key);
                $selectedFeatures[] = (int) $featureId;
            }
        }

        $data = array_filter($data, function ($key) {
            return !str_starts_with($key, 'feature_');
        }, ARRAY_FILTER_USE_KEY);

        $this->selectedFeatures = $selectedFeatures;

        unset($data['feature_sections']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (isset($this->selectedFeatures) && !empty($this->selectedFeatures)) {
            $propertyType = $this->getRecord();

            $featuresData = [];
            foreach ($this->selectedFeatures as $featureId) {
                $featuresData[$featureId] = [
                    'is_required_for_type' => false,
                    'order_for_type' => 0,
                ];
            }

            $propertyType->features()->sync($featuresData);
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tipo de propiedad creado exitosamente';
    }
}
