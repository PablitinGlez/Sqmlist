<?php

namespace App\Filament\Admin\Resources\PropertyTypeResource\Pages;

use App\Filament\Admin\Resources\PropertyTypeResource;
use App\Models\PropertyType;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPropertyType extends ViewRecord
{
    protected static string $resource = PropertyTypeResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
