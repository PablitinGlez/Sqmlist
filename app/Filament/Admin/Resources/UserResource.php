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
use Illuminate\Support\Facades\Hash; // Importar Hash para encriptar la contraseña
use Spatie\Permission\Models\Role; // Importar el modelo Role de Spatie
use Illuminate\Support\Facades\Auth; // Importar el facade Auth
use Filament\Notifications\Notification; // Importar Notification para mensajes de éxito/error
use Illuminate\Support\Collection; // Importar Collection para acciones masivas

class UserResource extends Resource
{
    // Asociar el recurso con el modelo User
    protected static ?string $model = User::class;

    // Icono que aparecerá en la navegación del panel de administración
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Etiqueta de navegación (singular)
    protected static ?string $modelLabel = 'Usuario';

    // Etiqueta de navegación (plural)
    protected static ?string $pluralModelLabel = 'Usuarios';

    // Grupo de navegación (opcional, para organizar en el sidebar)
    protected static ?string $navigationGroup = 'Gestión de Usuarios';

    // Mapeo de nombres de roles internos a nombres legibles en español
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
                    ->disabled(fn (string $operation): bool => $operation === 'edit'), // Deshabilitado en edición
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true) // Asegura que el email sea único, ignorando el usuario actual al editar
                    ->disabled(fn (string $operation): bool => $operation === 'edit'), // Deshabilitado en edición
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verificado En')
                    ->nullable()
                    ->native(false) // Para usar el selector de fecha de Filament
                    ->placeholder('No verificado')
                    ->disabled(fn (string $operation): bool => $operation === 'edit'), // Deshabilitado en edición
                
                // Campo de selección para el estado del usuario
                Forms\Components\Select::make('status')
                    ->label('Estado de la Cuenta')
                    ->options(User::STATUS_OPTIONS) // Usa las constantes definidas en el modelo User
                    ->required()
                    ->default(User::STATUS_ACTIVE), // Por defecto, una nueva cuenta está activa

                // Campos de contraseña (ocultos en edición)
                Forms\Components\TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create') // Requerido solo al crear
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)) // Encriptar la contraseña
                    ->dehydrated(fn (?string $state): bool => filled($state)) // No guardar si está vacío
                    ->revealable() // Permite mostrar/ocultar la contraseña
                    ->autocomplete('new-password')
                    ->hidden(fn (string $operation): bool => $operation === 'edit'), // Oculto en edición
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmar Contraseña')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create') // Requerido solo al crear
                    ->dehydrated(false) // No guardar este campo en la base de datos
                    ->revealable()
                    ->autocomplete('new-password')
                    ->same('password') // Debe ser igual al campo 'password'
                    ->hidden(fn (string $operation): bool => $operation === 'edit'), // Oculto en edición
                
                // Selector de Roles (usando Spatie Permissions)
                Forms\Components\Select::make('roles')
                    ->label('Roles')
                    ->multiple() // Permite seleccionar múltiples roles
                    ->relationship('roles', 'name') // Relación con el modelo Role de Spatie
                    // Usar options() para mapear los nombres de los roles a español
                    ->options(fn () => Role::all()->pluck('name', 'id')->map(fn ($name) => static::$roleLabels[$name] ?? $name)->toArray())
                    ->preload() // Carga todos los roles disponibles para el selector
                    ->searchable() // Permite buscar roles por nombre
                    ->required(), // Un usuario siempre debe tener al menos un rol
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
                    ->boolean() // Muestra un icono de check/cruz
                    ->sortable(),
                
                // Columna para el estado del usuario
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => User::STATUS_OPTIONS[$state] ?? $state) // Muestra el texto en español
                    ->colors([
                        'success' => User::STATUS_ACTIVE, // Verde para activo
                        'danger' => User::STATUS_INACTIVE, // Rojo para inactivo
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name') // Muestra los nombres de los roles
                    ->label('Roles')
                    ->badge() // Muestra los roles como "badges" (etiquetas)
                    // Usar formatStateUsing para traducir los nombres de los roles
                    ->formatStateUsing(fn (string $state): string => static::$roleLabels[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger', // Color rojo para el rol 'admin'
                        'agent' => 'info',   // Color azul claro para 'agent'
                        'owner' => 'warning', // Color amarillo para 'owner'
                        'real_estate_company' => 'success', // Color verde para 'real_estate_company'
                        'user' => 'gray', // Color gris para 'user'
                        default => 'gray', // Por defecto
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado En')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Oculto por defecto
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado En')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Oculto por defecto
            ])
            ->filters([
                // Filtro por roles
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Filtrar por Rol')
                    // Usar options() para el filtro de roles
                    ->options(fn () => Role::all()->pluck('name', 'name')->map(fn ($name) => static::$roleLabels[$name] ?? $name)->toArray()),
                
                // Filtro por estado de verificación de email
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verificado')
                    ->boolean(),

                // Filtro por estado de cuenta
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado de Cuenta')
                    ->options(User::STATUS_OPTIONS)
                    ->default(User::STATUS_ACTIVE) // Por defecto, mostrar solo activos
                    ->placeholder('Todos los estados'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Botón para editar
                
                // Acción para desactivar cuenta
                Tables\Actions\Action::make('deactivate')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation() // Pide confirmación antes de ejecutar
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
                    ->visible(fn (User $record): bool => $record->isActive()), // Solo visible si la cuenta está activa

                // Acción para activar cuenta
                Tables\Actions\Action::make('activate')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation() // Pide confirmación antes de ejecutar
                    ->action(function (User $record) {
                        $record->update(['status' => User::STATUS_ACTIVE]);
                        Notification::make()
                            ->title('Cuenta activada con éxito.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => $record->isInactive()), // Solo visible si la cuenta está inactiva
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Acción masiva para desactivar
                    Tables\Actions\BulkAction::make('deactivateSelected')
                        ->label('Desactivar Seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $loggedInUserId = Auth::id();
                            $recordsToDeactivate = $records->filter(fn ($record) => $record->id !== $loggedInUserId);
                            
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

                    // Acción masiva para activar
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
        return [
            // Aquí puedes definir Relation Managers si los necesitas, por ejemplo, para ProfileDetails
            // RelationManagers\ProfileDetailsRelationManager::class,
        ];
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
        // Obtener el ID del usuario autenticado
        $authenticatedUserId = Auth::id();

        // Excluir al usuario autenticado de la lista
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->where('id', '!=', $authenticatedUserId); // Excluir el ID del usuario actual
    }
}
