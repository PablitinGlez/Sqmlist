<?php

namespace App\Filament\Admin\Resources\PropertyTypeResource\Pages;

use App\Filament\Admin\Resources\PropertyTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyTypes extends ListRecords
{
    protected static string $resource = PropertyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
