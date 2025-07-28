<?php

namespace App\Filament\Admin\Resources\PropertyTypeResource\Pages;

use App\Filament\Admin\Resources\PropertyTypeResource;
use App\Models\FeatureSection;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyType extends EditRecord
{
    protected static string $resource = PropertyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $propertyType = $this->getRecord();

        $assignedFeatures = $propertyType->features()->pluck('features.id')->toArray();

        $sectionsWithFeatures = FeatureSection::whereHas('features', function ($query) use ($assignedFeatures) {
            $query->whereIn('features.id', $assignedFeatures);
        })->pluck('id')->toArray();

        $data['feature_sections'] = $sectionsWithFeatures;

        foreach ($assignedFeatures as $featureId) {
            $data["feature_{$featureId}"] = true;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
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

    protected function afterSave(): void
    {
        $propertyType = $this->getRecord();

        if (isset($this->selectedFeatures)) {
            $featuresData = [];
            foreach ($this->selectedFeatures as $featureId) {
                $featuresData[$featureId] = [
                    'is_required_for_type' => false,
                    'order_for_type' => 0,
                ];
            }

            $propertyType->features()->sync($featuresData);
        } else {
            $propertyType->features()->detach();
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Tipo de propiedad actualizado exitosamente';
    }
}
