<?php

namespace App\Filament\Admin\Resources\ContactResource\Pages;

use App\Filament\Admin\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

/**
 *  lista principal de mensajes de contacto y proporciona acceso a los mensajes archivados.
 */
class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected static ?string $title = 'Mensajes de Contacto';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_archived')
                ->label('Ver Archivados')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->url(ContactResource::getUrl('archived'))
                ->badge(fn(): int => ContactResource::getModel()::where('is_archived', true)->count())
                ->badgeColor('warning'),
        ];
    }
}
