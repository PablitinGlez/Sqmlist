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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // Asegura que el email sea único, ignorando el usuario actual al editar
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verificado En')
                    ->nullable()
                    ->native(false) // Para usar el selector de fecha de Filament
                    ->placeholder('No verificado'),
                Forms\Components\TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create') // Requerido solo al crear
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)) // Encriptar la contraseña
                    ->dehydrated(fn (?string $state): bool => filled($state)) // No guardar si está vacío
                    ->revealable() // Permite mostrar/ocultar la contraseña
                    ->autocomplete('new-password'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmar Contraseña')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create') // Requerido solo al crear
                    ->dehydrated(false) // No guardar este campo en la base de datos
                    ->revealable()
                    ->autocomplete('new-password')
                    ->same('password'), // Debe ser igual al campo 'password'
                
                // Selector de Roles (usando Spatie Permissions)
                Forms\Components\Select::make('roles')
                    ->label('Roles')
                    ->multiple() // Permite seleccionar múltiples roles
                    ->relationship('roles', 'name') // Relación con el modelo Role de Spatie
                    ->preload() // Carga todos los roles disponibles para el selector
                    ->searchable() // Permite buscar roles por nombre
                    // Opcional: Puedes añadir un valor por defecto para 'user' si todos los usuarios deben tener ese rol
                    // ->default(fn () => Role::where('name', 'user')->pluck('id'))
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
                Tables\Columns\TextColumn::make('roles.name') // Muestra los nombres de los roles
                    ->label('Roles')
                    ->badge() // Muestra los roles como "badges" (etiquetas)
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
                    ->label('Filtrar por Rol'),
                
                // Filtro por estado de verificación de email
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verificado')
                    ->nullableLabel('No verificado')
                    ->trueLabel('Verificado')
                    ->falseLabel('No verificado')
                    ->placeholder('Todos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Botón para editar
                Tables\Actions\DeleteAction::make(), // Botón para eliminar
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Acción masiva para eliminar
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
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
