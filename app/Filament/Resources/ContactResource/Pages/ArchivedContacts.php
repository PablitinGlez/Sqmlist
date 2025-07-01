<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

/**
 * Esta página gestiona la visualización y las acciones para los mensajes de contacto archivados.
 */
class ArchivedContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected static ?string $title = 'Mensajes Archivados';

    protected static ?string $navigationLabel = 'Mensajes Archivados';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Gestión de Contenidos';
    protected static ?int $navigationSort = 2;

    protected function getTableQuery(): Builder
    {
        return Contact::query()
            ->where('is_archived', true)
            ->orderByDesc('archived_at')
            ->orderByDesc('created_at');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Remitente')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_label')
                    ->label('Asunto')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Consulta General' => 'gray',
                        'Soporte Técnico' => 'info',
                        'Ventas' => 'success',
                        'Otros' => 'warning',
                        default => 'secondary',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_read')
                    ->label('Leído')
                    ->boolean()
                    ->action(
                        Action::make('toggle_is_read')
                            ->action(function (Contact $record): void {
                                $record->is_read = !$record->is_read;
                                $record->read_at = $record->is_read ? now() : null;
                                $record->save();

                                Notification::make()
                                    ->title('Estado de lectura actualizado')
                                    ->success()
                                    ->send();
                            })
                            ->successNotificationTitle('Estado de lectura actualizado')
                            ->icon(fn(bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->color(fn(bool $state): string => $state ? 'success' : 'gray')
                    )
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('archived_at')
                    ->label('Fecha de Archivo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha Recibido')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_read')
                    ->label('Estado de Lectura')
                    ->options([
                        null => 'Todos',
                        '0' => 'No Leídos',
                        '1' => 'Leídos',
                    ]),

                Tables\Filters\SelectFilter::make('subject')
                    ->label('Motivo del Mensaje')
                    ->options([
                        'consulta_general' => 'Consulta General',
                        'soporte_tecnico' => 'Soporte Técnico',
                        'ventas' => 'Ventas',
                        'otros' => 'Otros',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Action::make('unarchive')
                    ->label('Desarchivar')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Desarchivar mensaje')
                    ->modalDescription('¿Estás seguro de que deseas desarchivar este mensaje? Volverá a la lista principal.')
                    ->modalSubmitActionLabel('Sí, desarchivar')
                    ->action(function (Contact $record): void {
                        $record->unarchive();

                        Notification::make()
                            ->title('Mensaje desarchivado correctamente')
                            ->body('El mensaje ha sido movido a la lista principal.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar mensaje archivado')
                    ->modalDescription('¿Estás seguro de que deseas eliminar permanentemente este mensaje?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('unarchive')
                        ->label('Desarchivar seleccionados')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Desarchivar mensajes seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas desarchivar los mensajes seleccionados? Volverán a la lista principal.')
                        ->modalSubmitActionLabel('Sí, desarchivar')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            foreach ($records as $record) {
                                $record->unarchive();
                            }

                            Notification::make()
                                ->title('Mensajes desarchivados correctamente')
                                ->body('Los mensajes han sido movidos a la lista principal.')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar mensajes archivados')
                        ->modalDescription('¿Estás seguro de que deseas eliminar permanentemente los mensajes seleccionados?'),
                ]),
            ])
            ->emptyStateHeading('No hay mensajes archivados')
            ->emptyStateDescription('Los mensajes archivados aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-archive-box');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_contacts')
                ->label('Volver a Mensajes')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(ContactResource::getUrl('index')),
        ];
    }
}
