<?php

namespace App\Filament\Admin\Resources\PropertyTypeResource\Pages;

use App\Filament\Admin\Resources\PropertyTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyType extends EditRecord
{
    protected static string $resource = PropertyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
