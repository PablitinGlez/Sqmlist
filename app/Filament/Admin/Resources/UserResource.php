<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\ProfileDetails;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $navigationGroup = 'Gestión de Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Básica del Usuario')
                    ->schema([
                        Forms\Components\Placeholder::make('name')
                            ->label('Nombre')
                            ->content(fn(?Model $record): string => $record->name ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100']),

                        Forms\Components\Placeholder::make('email')
                            ->label('Email')
                            ->content(fn(?Model $record): string => $record->email ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100']),

                        Forms\Components\Placeholder::make('email_verified_at')
                            ->label('Email Verificado')
                            ->content(fn(?Model $record): string => $record->email_verified_at ? $record->email_verified_at->format('d/m/Y H:i') : 'No verificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100']),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                User::STATUS_ACTIVE => 'Activo',
                                User::STATUS_INACTIVE => 'Inactivo',
                            ])
                            ->default(User::STATUS_ACTIVE)
                            ->required(),

                        Forms\Components\Select::make('roles')
                            ->label('Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->options(function (?Model $record) {
                                $availableRoles = [];

                                $allRoles = Role::whereIn('name', ['agent', 'owner', 'real_estate_company', 'admin'])
                                    ->get()
                                    ->keyBy('name');

                                if ($record) {
                                    $currentRoles = $record->roles->pluck('name')->toArray();

                                    $advertiserRoles = ['agent', 'owner', 'real_estate_company'];
                                    $hasAdvertiserRole = !empty(array_intersect($currentRoles, $advertiserRoles));

                                    if ($hasAdvertiserRole) {
                                        foreach ($currentRoles as $roleName) {
                                            if (in_array($roleName, $advertiserRoles) && isset($allRoles[$roleName])) {
                                                $availableRoles[$allRoles[$roleName]->id] = self::getRoleLabel($roleName);
                                            }
                                        }
                                        if (isset($allRoles['admin'])) {
                                            $availableRoles[$allRoles['admin']->id] = self::getRoleLabel('admin');
                                        }
                                    } else {
                                        foreach (['agent', 'owner', 'real_estate_company', 'admin'] as $roleName) {
                                            if (isset($allRoles[$roleName])) {
                                                $availableRoles[$allRoles[$roleName]->id] = self::getRoleLabel($roleName);
                                            }
                                        }
                                    }

                                    return $availableRoles;
                                }

                                foreach (['agent', 'owner', 'real_estate_company', 'admin'] as $roleName) {
                                    if (isset($allRoles[$roleName])) {
                                        $availableRoles[$allRoles[$roleName]->id] = self::getRoleLabel($roleName);
                                    }
                                }

                                return $availableRoles;
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $role = Role::find($value);
                                return $role ? self::getRoleLabel($role->name) : '';
                            })
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        if (empty($value)) {
                                            return;
                                        }

                                        $selectedRoles = Role::whereIn('id', $value)->pluck('name')->toArray();
                                        $advertiserRoles = ['agent', 'owner', 'real_estate_company'];
                                        $selectedAdvertiserRoles = array_intersect($selectedRoles, $advertiserRoles);

                                        if (count($selectedAdvertiserRoles) > 1) {
                                            $fail('Solo puede asignar un rol de anunciante (Agente, Dueño o Inmobiliaria).');
                                        }
                                    };
                                },
                            ])
                            ->preload()
                            ->live(),
                    ])->columns(2),

                Forms\Components\Section::make('Información de Perfil de Negocio')
                    ->schema([
                        Forms\Components\Placeholder::make('profile_phone')
                            ->label('Teléfono Principal')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->phone_number ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100']),
                        Forms\Components\Placeholder::make('profile_whatsapp')
                            ->label('Número de WhatsApp')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->whatsapp_number ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100']),
                        Forms\Components\Placeholder::make('profile_contact_email')
                            ->label('Email de Contacto del Negocio')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->contact_email ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100']),

                        Forms\Components\Placeholder::make('profile_years_experience')
                            ->label('Años de Experiencia')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->years_experience ? $record->profileDetails->years_experience . ' años' : 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100'])
                            ->visible(
                                fn(Forms\Get $get) =>
                                collect($get('roles'))->contains(
                                    fn($roleId) =>
                                    Role::findById($roleId)?->name === 'agent' || Role::findById($roleId)?->name === 'real_estate_company'
                                )
                            ),
                        Forms\Components\Placeholder::make('profile_company')
                            ->label('Nombre de la Inmobiliaria/Empresa')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->real_estate_company ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100'])
                            ->visible(
                                fn(Forms\Get $get) =>
                                collect($get('roles'))->contains(
                                    fn($roleId) =>
                                    Role::findById($roleId)?->name === 'agent' || Role::findById($roleId)?->name === 'real_estate_company'
                                )
                            ),
                        Forms\Components\Placeholder::make('profile_rfc')
                            ->label('RFC')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->rfc ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100'])
                            ->visible(
                                fn(Forms\Get $get) =>
                                collect($get('roles'))->contains(
                                    fn($roleId) =>
                                    Role::findById($roleId)?->name === 'real_estate_company'
                                )
                            ),
                        Forms\Components\Placeholder::make('profile_biography')
                            ->label('Biografía/Descripción del Perfil')
                            ->content(fn(?Model $record): string => $record?->profileDetails?->biography ?? 'No especificado')
                            ->extraAttributes(['class' => 'border border-gray-300 rounded-lg px-3 py-2 text-gray-900 dark:border-gray-700 dark:text-gray-100 min-h-[80px] whitespace-pre-wrap'])
                            ->columnSpanFull(),

                        Forms\Components\Select::make('profile_status')
                            ->label('Estado del Perfil de Negocio')
                            ->options([
                                ProfileDetails::STATUS_ACTIVE => 'Activo',
                                ProfileDetails::STATUS_INACTIVE => 'Inactivo',
                            ])
                            ->default(ProfileDetails::STATUS_ACTIVE)
                            ->required()
                            ->visible(fn(?Model $record) => $record && $record->hasBusinessProfile()),
                    ])
                    ->columns(2)
                    ->visible(fn(?Model $record): bool => $record && $record->hasBusinessProfile()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_url')
                    ->label('Avatar')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(function ($record) {
                        return "https://ui-avatars.com/api/?name=" . urlencode($record->name) . "&color=7F9CF5&background=EBF4FF";
                    }),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Roles')
                    ->formatStateUsing(function (string $state, $record): string {
                        if ($record->roles->isEmpty()) {
                            return 'Usuario';
                        }
                        return self::getRoleLabel($state);
                    })
                    ->colors([
                        'success' => 'admin',
                        'warning' => 'agent',
                        'info' => 'owner',
                        'danger' => 'real_estate_company',
                        'primary' => fn($state, $record) => $record->roles->isEmpty(),
                    ])
                    ->placeholder('Usuario')
                    ->separator(', '),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(function (string $state, $record): string {
                        if ($record->trashed()) {
                            return 'Eliminado';
                        }
                        return User::STATUS_OPTIONS[$state] ?? $state;
                    })
                    ->colors([
                        'success' => User::STATUS_ACTIVE,
                        'danger' => User::STATUS_INACTIVE,
                        'secondary' => fn($state, $record) => $record->trashed(),
                    ]),

                Tables\Columns\TextColumn::make('login_method')
                    ->label('Método de registro')
                    ->formatStateUsing(function ($record): HtmlString {
                        if ($record->isGoogleLogin()) {
                            $googleIcon = '<svg width="20" height="20" viewBox="0 0 256 262" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid"><path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/><path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/><path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/><path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/></svg>';
                            return new HtmlString('<div class="flex items-center gap-2">' . $googleIcon . '<span>Google</span></div>');
                        } else {
                            $webIcon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>';
                            return new HtmlString('<div class="flex items-center gap-2">' . $webIcon . '<span>Web</span></div>');
                        }
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verificado')
                    ->icon(fn(string $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn(string $state): string => $state ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        User::STATUS_ACTIVE => 'Activo',
                        User::STATUS_INACTIVE => 'Inactivo',
                    ]),

                Tables\Filters\SelectFilter::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->options([
                        'agent' => 'Agente Inmobiliario',
                        'owner' => 'Dueño Directo',
                        'real_estate_company' => 'Inmobiliaria',
                        'admin' => 'Administrador'
                    ])
                    ->getOptionLabelUsing(function ($value) {
                        return self::getRoleLabel($value);
                    }),

                Tables\Filters\SelectFilter::make('login_method')
                    ->label('Método de Login')
                    ->options([
                        'google' => 'Google',
                        'web' => 'Web'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'] === 'google',
                                fn(Builder $query): Builder => $query->where('external_auth', 'google'),
                            )
                            ->when(
                                $data['value'] === 'web',
                                fn(Builder $query): Builder => $query->where('external_auth', '!=', 'google')->orWhereNull('external_auth'),
                            );
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('id', '!=', auth()->id())
                    ->withTrashed()
                    ->with('profileDetails');
            })
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar')
                    ->visible(fn($record) => !$record->trashed()),

                Tables\Actions\RestoreAction::make()
                    ->visible(fn($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

    private static function getRoleLabel(string $role): string
    {
        $labels = [
            'agent' => 'Agente Inmobiliario',
            'owner' => 'Dueño Directo',
            'real_estate_company' => 'Inmobiliaria',
            'admin' => 'Administrador',
            'user' => 'Usuario'
        ];

        return $labels[$role] ?? $role;
    }
}
