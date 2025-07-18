<?php

namespace App\Filament\Advertiser\Resources\PropertyResource\Pages;

use App\Filament\Advertiser\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Feature;
use App\Models\PropertyFeatureValue;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    
    protected array $featureValuesToSave = [];

    /**
     * 
     * 
     *
     * @param array 
     * @return array 
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
   
        return $data;
    }

    /**
     *
     * 
     *
     * @param array 
     * @return array 
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
 
        $featureValuesFromForm = $data['feature_values'] ?? [];
        unset($data['feature_values']);

        $this->featureValuesToSave = [];

        $propertyTypeId = $this->record->property_type_id;


        $features = Feature::whereHas('propertyTypes', function ($query) use ($propertyTypeId) {
            $query->where('property_types.id', $propertyTypeId);
        })->get()->keyBy('slug');

      
        foreach ($featureValuesFromForm as $featureSlug => $value) {
            $feature = $features->get($featureSlug);

            if ($feature) {
                $processedValue = $value;

            
                if ($feature->data_type === 'boolean') {
                    $processedValue = (bool) $value ? 1 : 0;
                } elseif ($feature->data_type === 'array' || $feature->data_type === 'json') {
                    $processedValue = json_encode($value);
                }

                if ($processedValue === '') {
                    $processedValue = null;
                }

                $this->featureValuesToSave[] = [
                    'feature_id' => $feature->id,
                    'value' => $processedValue,
                ];
            }
        }

      
        if (isset($data['images'])) {
            $this->processImages($data['images']);
            unset($data['images']);
        }

        return $data;
    }

    /**
     *
     */
    protected function processImages(array $newImagePaths): void
    {
     
        $currentImages = $this->record->images()->orderBy('order')->get();
        $currentImagePaths = $currentImages->pluck('path')->toArray();

        $imagesToDelete = array_diff($currentImagePaths, $newImagePaths);

        $imagesToAdd = array_diff($newImagePaths, $currentImagePaths);

        if (!empty($imagesToDelete)) {
            foreach ($imagesToDelete as $pathToDelete) {
                $imageRecord = $currentImages->firstWhere('path', $pathToDelete);
                if ($imageRecord) {
              
                    if (Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                  
                    $imageRecord->delete();
                }
            }
        }

  
        if (!empty($imagesToAdd)) {
            $maxOrder = $this->record->images()->max('order') ?? 0;

            foreach ($imagesToAdd as $newPath) {
                $maxOrder++;
                PropertyImage::create([
                    'property_id' => $this->record->id,
                    'path' => $newPath,
                    'order' => $maxOrder,
                    'is_featured' => $this->record->images()->count() === 0, 
                ]);
            }
        }

       
        $this->updateImageOrder($newImagePaths);
    }

    /**
     * 
     */
    protected function updateImageOrder(array $orderedPaths): void
    {
        foreach ($orderedPaths as $index => $path) {
            PropertyImage::where('property_id', $this->record->id)
                ->where('path', $path)
                ->update(['order' => $index + 1]);
        }
    }

    /**
     * 
     * 
     */
    protected function afterSave(): void
    {
 
        foreach ($this->featureValuesToSave as $featureValueData) {
            $this->record->featureValues()->updateOrCreate(
                ['feature_id' => $featureValueData['feature_id']],
                ['value' => $featureValueData['value']]
            );
        }

     
        \Filament\Notifications\Notification::make()
            ->title('Propiedad actualizada exitosamente.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
