<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactResource\Pages;

use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

/**
 *  gestiona la visualización, edición, archivado y eliminación de mensajes de contacto.
 */
class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Mensajes de Contacto';

    protected static ?string $navigationGroup = 'Gestión de Contenidos';

    protected static ?int $navigationSort = 1;

    public static function getSlug(): string
    {
        return 'contactos';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalles del Mensaje')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Nombre')
                            ->disabled()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Apellido')
                            ->disabled()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->disabled()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->disabled()
                            ->maxLength(20),

                        Forms\Components\Select::make('subject')
                            ->label('Motivo')
                            ->options([
                                'consulta_general' => 'Consulta General',
                                'soporte_tecnico' => 'Soporte Técnico',
                                'ventas' => 'Ventas',
                                'otros' => 'Otros',
                            ])
                            ->disabled()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Contenido del Mensaje')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Mensaje')
                            ->rows(10)
                            ->disabled()
                            ->required()
                            ->string()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Estado del Mensaje')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_read')
                            ->label('Mensaje Leído')
                            ->helperText('Marca para indicar que el mensaje ha sido revisado.')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Forms\Components\Toggle $component, $state, Forms\Set $set) {
                                if ($state && !$component->getRecord()->read_at) {
                                    $set('read_at', now());
                                } elseif (!$state) {
                                    $set('read_at', null);
                                }
                            }),

                        Forms\Components\DateTimePicker::make('read_at')
                            ->label('Fecha de Lectura')
                            ->native(false)
                            ->placeholder('Se establecerá automáticamente al marcar como leído')
                            ->disabled(fn(Forms\Get $get): bool => !$get('is_read')),

                        Forms\Components\Toggle::make('is_archived')
                            ->label('Mensaje Archivado')
                            ->helperText('Marca para archivar el mensaje.')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Forms\Components\Toggle $component, $state, Forms\Set $set) {
                                if ($state && !$component->getRecord()->archived_at) {
                                    $set('archived_at', now());
                                } elseif (!$state) {
                                    $set('archived_at', null);
                                }
                            }),

                        Forms\Components\DateTimePicker::make('archived_at')
                            ->label('Fecha de Archivo')
                            ->native(false)
                            ->placeholder('Se establecerá automáticamente al archivar')
                            ->disabled(fn(Forms\Get $get): bool => !$get('is_archived')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha Recibido')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('read_at')
                    ->label('Fecha de Lectura')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_read')
                    ->label('Estado de Lectura')
                    ->options([
                        null => 'Todos',
                        '0' => 'No Leídos',
                        '1' => 'Leídos',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (array_key_exists('value', $data) && $data['value'] !== null) {
                            return $query->where('is_read', $data['value']);
                        }
                        return $query;
                    }),

                SelectFilter::make('subject')
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
                Tables\Actions\EditAction::make(),
                Action::make('archive')
                    ->label('Archivar')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->visible(fn(Contact $record): bool => !$record->is_archived)
                    ->requiresConfirmation()
                    ->modalHeading('Archivar mensaje')
                    ->modalDescription('¿Estás seguro de que deseas archivar este mensaje? Se moverá a la sección de archivados.')
                    ->modalSubmitActionLabel('Sí, archivar')
                    ->action(function (Contact $record): void {
                        $record->archive();

                        Notification::make()
                            ->title('Mensaje archivado correctamente')
                            ->body('El mensaje se ha movido a la sección de archivados.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Archivar seleccionados')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Archivar mensajes seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas archivar los mensajes seleccionados? Se moverán a la sección de archivados.')
                        ->modalSubmitActionLabel('Sí, archivar')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            foreach ($records as $record) {
                                $record->archive();
                            }

                            Notification::make()
                                ->title('Mensajes archivados correctamente')
                                ->body('Los mensajes se han movido a la sección de archivados.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'archived' => Pages\ArchivedContacts::route('/archived'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_archived', false)
            ->orderBy('is_read')
            ->orderByDesc('created_at');
    }
}
