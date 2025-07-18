<?php

namespace App\Filament\Advertiser\Resources\PropertyContactResource\Pages;

use App\Filament\Advertiser\Resources\PropertyContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\PropertyContact; // Asegúrate de importar el modelo

class ListPropertyContacts extends ListRecords
{
    protected static string $resource = PropertyContactResource::class;

    protected static ?string $title = 'Mensajes Recibidos'; 

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_archived_property_contacts')
                ->label('Ver Archivados')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->url(PropertyContactResource::getUrl('archived'))
                ->badge(fn(): int => PropertyContactResource::getModel()::where('is_archived', true)
                    ->whereHas('property', function ($query) {
                        $query->where('user_id', auth()->id());
                    })->count()) 
                ->badgeColor('warning'),
        ];
    }
}
