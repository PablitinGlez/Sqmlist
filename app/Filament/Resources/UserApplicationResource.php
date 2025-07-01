<?php

namespace App\Filament\Resources;

use App\Models\UserApplication;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserApplicationResource\Pages;
use App\Traits\AdminOnlyResourceTrait;

/**
 * Recurso para gestionar las solicitudes de perfil de usuario en el panel de administración.
 */
class UserApplicationResource extends Resource
{
    use AdminOnlyResourceTrait;

    protected static ?string $model = UserApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Solicitudes de Perfil';
    protected static ?string $modelLabel = 'Solicitud de Perfil';
    protected static ?string $pluralModelLabel = 'Solicitudes de Perfil';
    protected static ?string $navigationGroup = 'Gestión de Solicitudes';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Solicitante')
                    ->schema([
                        Forms\Components\Placeholder::make('user_name')
                            ->label('Nombre del Usuario')
                            ->content(fn(?UserApplication $record) => new HtmlString(
                                view('filament.forms.components.disabled-text-input-placeholder', [
                                    'slot' => $record?->user?->name ?? '<span class="text-gray-500 dark:text-gray-400">No disponible</span>'
                                ])->render()
                            )),
                        Forms\Components\Placeholder::make('user_email')
                            ->label('Email del Usuario')
                            ->content(fn(?UserApplication $record) => new HtmlString(
                                view('filament.forms.components.disabled-text-input-placeholder', [
                                    'slot' => $record?->user?->email ?? '<span class="text-gray-500 dark:text-gray-400">No disponible</span>'
                                ])->render()
                            )),
                        Forms\Components\Placeholder::make('requested_user_type_display')
                            ->label('Tipo de Perfil Solicitado')
                            ->content(
                                fn(?UserApplication $record): string =>
                                $record ? (UserApplication::TYPE_OPTIONS[$record->requested_user_type] ?? 'Desconocido') : 'No disponible'
                            ),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Datos de Contacto')
                    ->schema([
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Teléfono Principal')
                            ->disabled(),
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->label('WhatsApp')
                            ->disabled(),
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email de Contacto')
                            ->disabled(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Información Profesional')
                    ->schema([
                        Forms\Components\TextInput::make('years_experience')
                            ->label('Años de Experiencia')
                            ->disabled()
                            ->visible(
                                fn(?UserApplication $record): bool =>
                                $record && in_array($record->requested_user_type, [
                                    UserApplication::TYPE_AGENT,
                                    UserApplication::TYPE_REAL_ESTATE_COMPANY,
                                ])
                            ),
                        Forms\Components\TextInput::make('real_estate_company')
                            ->label('Inmobiliaria / Empresa')
                            ->disabled()
                            ->formatStateUsing(
                                fn(?string $state): string =>
                                empty($state) || is_null($state) || trim($state) === ''
                                    ? 'No especificado'
                                    : $state
                            )
                            ->visible(
                                fn(?UserApplication $record): bool =>
                                $record && in_array($record->requested_user_type, [
                                    UserApplication::TYPE_AGENT,
                                    UserApplication::TYPE_REAL_ESTATE_COMPANY,
                                ])
                            ),
                        Forms\Components\TextInput::make('identification_type')
                            ->label('Tipo de Identificación')
                            ->disabled(),
                        Forms\Components\TextInput::make('rfc')
                            ->label('RFC')
                            ->disabled()
                            ->visible(
                                fn(?UserApplication $record): bool =>
                                $record && $record->requested_user_type === UserApplication::TYPE_REAL_ESTATE_COMPANY
                            ),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Documentos')
                    ->schema([
                        Forms\Components\Placeholder::make('identification_document')
                            ->label('Documento de Identificación')
                            ->content(function (?UserApplication $record): HtmlString {
                                if (!$record) {
                                    return new HtmlString('<p class="text-gray-500 dark:text-gray-400">Documento no disponible.</p>');
                                }
                                return new HtmlString(
                                    view('filament.forms.components.document-card', [
                                        'record' => $record,
                                        'title' => 'Identificación Oficial',
                                        'description' => 'Documento de identidad',
                                        'filePath' => $record->identification_path,
                                        'buttonText' => 'Ver Documento',
                                    ])->render()
                                );
                            }),
                        Forms\Components\Placeholder::make('license_document')
                            ->label('Licencia / Certificación Profesional')
                            ->content(function (?UserApplication $record): HtmlString {
                                if (
                                    !$record ||
                                    empty($record->license_path) ||
                                    !in_array($record->requested_user_type, [
                                        UserApplication::TYPE_AGENT,
                                        UserApplication::TYPE_REAL_ESTATE_COMPANY,
                                    ])
                                ) {
                                    return new HtmlString('<p class="text-gray-500 dark:text-gray-400">No aplica o no se proporcionó.</p>');
                                }
                                return new HtmlString(
                                    view('filament.forms.components.document-card', [
                                        'record' => $record,
                                        'title' => 'Licencia Profesional',
                                        'description' => 'Certificación inmobiliaria / empresarial',
                                        'filePath' => $record->license_path,
                                        'buttonText' => 'Ver Licencia',
                                    ])->render()
                                );
                            })
                            ->visible(function (?UserApplication $record): bool {
                                return $record && in_array($record->requested_user_type, [
                                    UserApplication::TYPE_AGENT,
                                    UserApplication::TYPE_REAL_ESTATE_COMPANY,
                                ]);
                            }),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Inspección de la Solicitud')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                UserApplication::STATUS_PENDING => 'Pendiente',
                                UserApplication::STATUS_APPROVED => 'Aprobada',
                                UserApplication::STATUS_REJECTED => 'Rechazada',
                            ])
                            ->default(UserApplication::STATUS_PENDING)
                            ->required()
                            ->live()
                            ->selectablePlaceholder(false),
                        Forms\Components\Textarea::make('status_message')
                            ->label('Motivo del Rechazo')
                            ->placeholder('Explica detalladamente por qué se rechaza esta solicitud...')
                            ->rows(4)
                            ->required()
                            ->visible(fn(Forms\Get $get): bool => $get('status') === UserApplication::STATUS_REJECTED),
                    ])
                    ->columns(1)
                    ->visible(fn(?UserApplication $record): bool => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\BadgeColumn::make('requested_user_type')
                    ->label('Tipo de Perfil')
                    ->colors([
                        'primary' => UserApplication::TYPE_OWNER,
                        'info' => UserApplication::TYPE_AGENT,
                        'secondary' => UserApplication::TYPE_REAL_ESTATE_COMPANY,
                    ])
                    ->formatStateUsing(fn(string $state): string => UserApplication::TYPE_OPTIONS[$state] ?? 'Desconocido')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => UserApplication::STATUS_PENDING,
                        'success' => UserApplication::STATUS_APPROVED,
                        'danger' => UserApplication::STATUS_REJECTED,
                    ])
                    ->icons([
                        'heroicon-o-clock' => UserApplication::STATUS_PENDING,
                        'heroicon-o-check-circle' => UserApplication::STATUS_APPROVED,
                        'heroicon-o-x-circle' => UserApplication::STATUS_REJECTED,
                    ])
                    ->formatStateUsing(function (string $state, ?Model $record): string {
                        return $record ? $record->status_human_readable : 'Desconocido';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->tooltip(fn(?UserApplication $record) => $record?->created_at?->format('d/m/Y H:i:s') ?? 'No disponible'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        UserApplication::STATUS_PENDING => 'Pendiente',
                        UserApplication::STATUS_APPROVED => 'Aprobada',
                        UserApplication::STATUS_REJECTED => 'Rechazada',
                    ]),
                Tables\Filters\SelectFilter::make('requested_user_type')
                    ->label('Tipo de Perfil')
                    ->options(UserApplication::TYPE_OPTIONS ?? []),
                Tables\Filters\Filter::make('recent')
                    ->label('Recientes (últimos 7 días)')
                    ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\Action::make('inspect')
                    ->label('Inspeccionar')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->button()
                    ->url(
                        fn(UserApplication $record): string => static::getUrl('inspect', ['record' => $record])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserApplications::route('/'),
            'inspect' => Pages\InspectUserApplication::route('/{record}/inspect'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', UserApplication::STATUS_PENDING)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
