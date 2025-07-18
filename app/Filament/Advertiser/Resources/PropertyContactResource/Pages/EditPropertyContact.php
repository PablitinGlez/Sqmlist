<?php

namespace App\Filament\Advertiser\Resources\PropertyContactResource\Pages;

use App\Filament\Advertiser\Resources\PropertyContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyContact extends EditRecord
{
    protected static string $resource = PropertyContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
