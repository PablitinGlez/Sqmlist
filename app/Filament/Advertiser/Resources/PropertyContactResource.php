<?php

namespace App\Filament\Advertiser\Resources;

use App\Filament\Advertiser\Resources\PropertyContactResource\Pages;
use App\Models\PropertyContact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup; // Importar ActionGroup

class PropertyContactResource extends Resource
{
    protected static ?string $model = PropertyContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Mensajes';
    protected static ?string $navigationLabel = 'Contactos de Propiedades';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Detalles del Mensaje')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->relationship('property', 'title')
                            ->label('Propiedad Contactada')
                            ->disabled()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('sender_name')
                            ->label('Nombre del Remitente')
                            ->readOnly()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('sender_email')
                            ->label('Email del Remitente')
                            ->readOnly()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('sender_phone')
                            ->label('Teléfono del Remitente')
                            ->readOnly()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('message_text')
                            ->label('Mensaje')
                            ->rows(5)
                            ->readOnly()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Fieldset::make('Estado del Mensaje')
                    ->schema([
                        Forms\Components\Toggle::make('is_archived')
                            ->label('Archivado')
                            ->helperText('Activar para archivar este mensaje.')
                            ->live()
                            ->afterStateUpdated(function (Forms\Components\Toggle $component, $state, $record) {
                                if ($record) {
                                    if ($state) {
                                        $record->archive();
                                    } else {
                                        $record->unarchive();
                                    }
                                    Notification::make()
                                        ->title('Estado de archivo actualizado')
                                        ->success()
                                        ->send();
                                }
                            }),
                        Forms\Components\DateTimePicker::make('archived_at')
                            ->label('Archivado el')
                            ->readOnly()
                            ->placeholder('No archivado aún'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nueva columna para la imagen de la propiedad
                Tables\Columns\ImageColumn::make('property.featuredImage.path')
                    ->label('Imagen')
                    ->width(80) // Ancho de la imagen en la tabla
                    ->height(50) // Alto de la imagen en la tabla
                    ->circular() // Hace la imagen circular (opcional, puedes quitarlo)
                    ->defaultImageUrl(url('images/placeholder.png')) // Imagen de fallback si no hay imagen principal
                    ->tooltip(fn(PropertyContact $record): string => $record->property->title ?? 'Propiedad sin título')
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('property.title')
                    ->label('Propiedad Contactada')
                    ->searchable()
                    ->sortable()
                    ->url(fn(PropertyContact $record): string => route('properties.show', $record->property->slug ?? '#'), true)
                    ->tooltip(fn(PropertyContact $record): string => 'Ver propiedad en el sitio público'),

                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Remitente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender_email')
                    ->label('Email')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender_phone')
                    ->label('Teléfono')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('message_text')
                    ->label('Mensaje')
                    ->limit(70)
                    ->tooltip(fn(PropertyContact $record): string => $record->message_text)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha Recepción')
                    ->dateTime()
                    ->sortable()
                    ->description(fn(PropertyContact $record): string => $record->created_at->diffForHumans())
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('archived_at')
                    ->label('Fecha Archivo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_archived')
                    ->label('Estado de Archivo')
                    ->placeholder('Todos')
                    ->trueLabel('Solo Archivados')
                    ->falseLabel('Solo No Archivados')
                    ->nullable(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Ver Detalles'),

                    Action::make('archive')
                        ->label('Archivar')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->visible(fn(PropertyContact $record): bool => !$record->is_archived)
                        ->requiresConfirmation()
                        ->modalHeading('Archivar mensaje')
                        ->modalDescription('¿Estás seguro de que deseas archivar este mensaje? Se moverá a la sección de archivados.')
                        ->modalSubmitActionLabel('Sí, archivar')
                        ->action(function (PropertyContact $record): void {
                            $record->archive();
                            Notification::make()
                                ->title('Mensaje archivado correctamente')
                                ->body('El mensaje se ha movido a la sección de archivados.')
                                ->success()
                                ->send();
                        }),

                    Action::make('unarchive')
                        ->label('Desarchivar')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('success')
                        ->visible(fn(PropertyContact $record): bool => $record->is_archived)
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

                    Tables\Actions\DeleteAction::make()
                        ->label('Eliminar')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar mensaje')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(fn() => !Auth::user()->hasRole('admin')),

                    Tables\Actions\BulkAction::make('archive_selected_messages')
                        ->label('Archivar seleccionados')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Archivar mensajes seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas archivar los mensajes seleccionados? Se moverán a la sección de archivados.')
                        ->modalSubmitActionLabel('Sí, archivar')
                        ->action(fn(\Illuminate\Database\Eloquent\Collection $records) => $records->each(fn(PropertyContact $record) => $record->archive())),

                    Tables\Actions\BulkAction::make('unarchive_selected_messages')
                        ->label('Desarchivar seleccionados')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Desarchivar mensajes seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas desarchivar los mensajes seleccionados? Volverán a la lista principal.')
                        ->modalSubmitActionLabel('Sí, desarchivar')
                        ->action(fn(\Illuminate\Database\Eloquent\Collection $records) => $records->each(fn(PropertyContact $record) => $record->unarchive())),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertyContacts::route('/'),
            'archived' => Pages\ArchivedPropertyContacts::route('/archived'),
            'view' => Pages\ViewPropertyContact::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        $query->whereHas('property', function (Builder $propertyQuery) use ($user) {
            $propertyQuery->where('user_id', $user->id);
        });

        return $query->where('is_archived', false)
            ->orderByDesc('created_at');
    }
}
