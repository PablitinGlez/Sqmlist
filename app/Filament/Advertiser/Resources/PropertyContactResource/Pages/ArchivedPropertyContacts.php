<?php

namespace App\Filament\Advertiser\Resources\PropertyContactResource\Pages;

use App\Filament\Advertiser\Resources\PropertyContactResource;
use App\Models\PropertyContact;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;  // ← IMPORT AÑADIDO
use Filament\Tables\Columns\TextColumn;   // ← IMPORT AÑADIDO
use Filament\Tables\Filters\TernaryFilter; // ← IMPORT AÑADIDO
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;        // ← IMPORT AÑADIDO
use Filament\Tables\Actions\DeleteAction;      // ← IMPORT AÑADIDO
use Filament\Tables\Actions\BulkActionGroup;   // ← IMPORT AÑADIDO
use Filament\Tables\Actions\DeleteBulkAction;  // ← IMPORT AÑADIDO
use Filament\Tables\Actions\BulkAction;        // ← IMPORT AÑADIDO

class ArchivedPropertyContacts extends ListRecords
{
    protected static string $resource = PropertyContactResource::class;

    protected static ?string $title = 'Mensajes Archivados';
    protected static ?string $navigationLabel = 'Mensajes Archivados';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Mensajes';
    protected static ?int $navigationSort = 2;

    protected function getTableQuery(): Builder
    {
        $query = PropertyContact::query()
            ->where('is_archived', true)
            ->orderByDesc('archived_at') // Ordenar por fecha de archivo
            ->orderByDesc('created_at'); // Luego por fecha de creación

        $user = Auth::user();

        // ¡MUY IMPORTANTE! Asegurarse de que el anunciante solo vea sus propios mensajes archivados
        $query->whereHas('property', function (Builder $propertyQuery) use ($user) {
            $propertyQuery->where('user_id', $user->id);
        });

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery()) // Usa la consulta de esta página
            ->columns([
                // Nueva columna para la imagen de la propiedad
                ImageColumn::make('property.featuredImage.path')  // ← CAMBIADO DE Tables\Columns\ImageColumn
                    ->label('Imagen')
                    ->width(80)
                    ->height(50)
                    ->circular()
                    ->defaultImageUrl(url('images/placeholder.png'))
                    ->tooltip(fn(PropertyContact $record): string => $record->property->title ?? 'Propiedad sin título')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('property.title')  // ← CAMBIADO DE Tables\Columns\TextColumn
                    ->label('Propiedad Contactada')
                    ->searchable()
                    ->sortable()
                    ->url(fn(PropertyContact $record): string => route('properties.show', $record->property->slug ?? '#'), true)
                    ->tooltip(fn(PropertyContact $record): string => 'Ver propiedad en el sitio público'),

                TextColumn::make('sender_name')
                    ->label('Remitente')
                    ->searchable(),

                TextColumn::make('sender_email')
                    ->label('Email')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('sender_phone')
                    ->label('Teléfono')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('message_text')
                    ->label('Mensaje')
                    ->limit(70)
                    ->tooltip(fn(PropertyContact $record): string => $record->message_text)
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Fecha Recepción')
                    ->dateTime()
                    ->sortable()
                    ->description(fn(PropertyContact $record): string => $record->created_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('archived_at')
                    ->label('Fecha Archivo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Mantener visible en la página de archivados
            ])
            ->filters([
                // Mantener el filtro de archivado, aunque en esta página siempre serán archivados
                TernaryFilter::make('is_archived')
                    ->label('Estado de Archivo')
                    ->placeholder('Todos')
                    ->trueLabel('Solo Archivados')
                    ->falseLabel('Solo No Archivados')
                    ->nullable(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()  // ← CAMBIADO DE Tables\Actions\ViewAction
                        ->label('Ver Detalles'),

                    Action::make('unarchive')
                        ->label('Desarchivar')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Desarchivar mensaje')
                        ->modalDescription('¿Estás seguro de que deseas desarchivar este mensaje? Volverá a la lista principal.')
                        ->modalSubmitActionLabel('Sí, desarchivar')
                        ->action(function (PropertyContact $record): void {
                            $record->unarchive();
                            Notification::make()
                                ->title('Mensaje desarchivado correctamente')
                                ->body('El mensaje ha sido movido a la lista principal.')
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make()  // ← CAMBIADO DE Tables\Actions\DeleteAction
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar mensaje archivado')
                        ->modalDescription('¿Estás seguro de que deseas eliminar permanentemente este mensaje? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->action(function (PropertyContact $record): void {
                            $record->delete();
                            Notification::make()
                                ->title('Mensaje eliminado correctamente')
                                ->body('El mensaje ha sido eliminado de forma permanente.')
                                ->success()
                                ->send();
                        }),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([  // ← CAMBIADO DE Tables\Actions\BulkActionGroup
                    DeleteBulkAction::make()  // ← CAMBIADO DE Tables\Actions\DeleteBulkAction
                        ->hidden(fn() => !Auth::user()->hasRole('admin')), // Control de acceso para eliminar masivamente

                    BulkAction::make('unarchive_selected_messages')  // ← CAMBIADO DE Tables\Actions\BulkAction
                        ->label('Desarchivar seleccionados')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Desarchivar mensajes seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas desarchivar los mensajes seleccionados? Volverán a la lista principal.')
                        ->modalSubmitActionLabel('Sí, desarchivar')
                        ->action(fn(\Illuminate\Database\Eloquent\Collection $records) => $records->each(fn(PropertyContact $record) => $record->unarchive())),
                ]),
            ])
            ->emptyStateHeading('No hay mensajes archivados')
            ->emptyStateDescription('Los mensajes archivados de tus propiedades aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-archive-box');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_active_property_contacts')
                ->label('Volver a Mensajes Recibidos')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(PropertyContactResource::getUrl('index')),
        ];
    }
}
