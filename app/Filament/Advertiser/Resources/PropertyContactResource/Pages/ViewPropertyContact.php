<?php

namespace App\Filament\Advertiser\Resources\PropertyContactResource\Pages;

use App\Filament\Advertiser\Resources\PropertyContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPropertyContact extends ViewRecord
{
    protected static string $resource = PropertyContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
        
        ];
    }
}
