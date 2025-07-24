<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $navigationGroup = 'Gestión de Usuarios';

    protected static array $roleLabels = [
        'admin' => 'Administrador',
        'agent' => 'Agente Inmobiliario',
        'owner' => 'Dueño Directo',
        'real_estate_company' => 'Inmobiliaria / Desarrolladora',
        'user' => 'Usuario General',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn(string $operation): bool => $operation === 'edit'),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn(string $operation): bool => $operation === 'edit'),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verificado En')
                    ->nullable()
                    ->native(false)
                    ->placeholder('No verificado')
                    ->disabled(fn(string $operation): bool => $operation === 'edit'),

                Forms\Components\Select::make('status')
                    ->label('Estado de la Cuenta')
                    ->options(User::STATUS_OPTIONS)
                    ->required()
                    ->default(User::STATUS_ACTIVE),

                Forms\Components\TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->revealable()
                    ->autocomplete('new-password')
                    ->hidden(fn(string $operation): bool => $operation === 'edit'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmar Contraseña')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrated(false)
                    ->revealable()
                    ->autocomplete('new-password')
                    ->same('password')
                    ->hidden(fn(string $operation): bool => $operation === 'edit'),

                Forms\Components\Select::make('roles')
                    ->label('Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->options(fn() => Role::all()->pluck('name', 'id')->map(fn($name) => static::$roleLabels[$name] ?? $name)->toArray())
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verificado')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn(string $state): string => User::STATUS_OPTIONS[$state] ?? $state)
                    ->colors([
                        'success' => User::STATUS_ACTIVE,
                        'danger' => User::STATUS_INACTIVE,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => static::$roleLabels[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'agent' => 'info',
                        'owner' => 'warning',
                        'real_estate_company' => 'success',
                        'user' => 'gray',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado En')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado En')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Filtrar por Rol')
                    ->options(fn() => Role::all()->pluck('name', 'name')->map(fn($name) => static::$roleLabels[$name] ?? $name)->toArray()),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verificado')
                    ->boolean(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado de Cuenta')
                    ->options(User::STATUS_OPTIONS)
                    ->default(User::STATUS_ACTIVE)
                    ->placeholder('Todos los estados'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('deactivate')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        if ($record->id === Auth::id()) {
                            Notification::make()
                                ->title('No se puede desactivar tu propia cuenta.')
                                ->danger()
                                ->send();
                            return;
                        }
                        $record->update(['status' => User::STATUS_INACTIVE]);
                        Notification::make()
                            ->title('Cuenta desactivada con éxito.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(User $record): bool => $record->isActive()),

                Tables\Actions\Action::make('activate')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['status' => User::STATUS_ACTIVE]);
                        Notification::make()
                            ->title('Cuenta activada con éxito.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(User $record): bool => $record->isInactive()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('deactivateSelected')
                        ->label('Desactivar Seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $loggedInUserId = Auth::id();
                            $recordsToDeactivate = $records->filter(fn($record) => $record->id !== $loggedInUserId);

                            if ($recordsToDeactivate->isEmpty()) {
                                Notification::make()
                                    ->title('No se pueden desactivar las cuentas seleccionadas (incluye tu propia cuenta o ya están inactivas).')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $recordsToDeactivate->each->update(['status' => User::STATUS_INACTIVE]);
                            Notification::make()
                                ->title('Cuentas desactivadas con éxito.')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('activateSelected')
                        ->label('Activar Seleccionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => User::STATUS_ACTIVE]);
                            Notification::make()
                                ->title('Cuentas activadas con éxito.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $authenticatedUserId = Auth::id();

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->where('id', '!=', $authenticatedUserId);
    }
}
