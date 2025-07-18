<?php

namespace App\Filament\Advertiser\Resources;

use App\Filament\Advertiser\Resources\NotificationResource\Pages;
use App\Filament\Advertiser\Resources\NotificationResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Notifications\DatabaseNotification; 
use Filament\Notifications\Notification; 

class NotificationResource extends Resource
{
    
    protected static ?string $model = DatabaseNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell'; 
    protected static ?string $navigationLabel = 'Mis Notificaciones';
    protected static ?string $navigationGroup = 'Mi Actividad'; 
    protected static ?int $navigationSort = 10; 

    /**
     * 
     * 
     */
    public static function getEloquentQuery(): Builder
    {
        // Obtener el query base
        $query = parent::getEloquentQuery();

        //
        // 
        // 
        return $query
            ->where('notifiable_type', \App\Models\User::class) 
            ->where('notifiable_id', auth()->id()) 
            ->orderBy('created_at', 'desc'); 
    }

    public static function form(Form $form): Form
    {
     

        return $form
            ->schema([
                Forms\Components\Placeholder::make('view_only')
                    ->content('Este es un recurso de solo lectura. Las notificaciones no pueden ser editadas directamente.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('data.icon')
                    ->label('Tipo')
                    ->icon(function ($record): string {
                      
                        $iconName = $record->data['icon'] ?? 'bell';

                       
                        $iconMap = [
                            'check' => 'heroicon-o-check-circle',
                            'info' => 'heroicon-o-information-circle',
                            'warning' => 'heroicon-o-exclamation-triangle',
                            'error' => 'heroicon-o-x-circle',
                            'success' => 'heroicon-o-check-circle',
                            'bell' => 'heroicon-o-bell',
                            'mail' => 'heroicon-o-envelope',
                            'user' => 'heroicon-o-user',
                            'settings' => 'heroicon-o-cog-6-tooth',
                            'notification' => 'heroicon-o-bell',
                        ];

                       
                        if (isset($iconMap[$iconName])) {
                            return $iconMap[$iconName];
                        }

                      
                        if (strpos($iconName, 'heroicon-') === false) {
                            $iconName = 'heroicon-o-' . $iconName;
                        }

                        return $iconName;
                    })
                    ->color(function ($record): string {
                        return $record->data['color'] ?? 'gray';
                    }),

                Tables\Columns\TextColumn::make('data.title') 
                    ->label('Asunto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('data.body') 
                    ->label('Mensaje')
                    ->searchable()
                    ->wrap(), 

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recibida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('read_at')
                    ->label('Leída')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn(?string $state): string => $state ? 'Sí' : 'No leída')
                    ->badge()
                    ->color(fn(?string $state): string => $state ? 'success' : 'warning'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('read_at')
                    ->label('Estado de Lectura')
                    ->placeholder('Todas')
                    ->trueLabel('Leídas')
                    ->falseLabel('No Leídas')
                    ->nullable(), 
            ])
            ->actions([
                Tables\Actions\Action::make('markAsRead')
                    ->label('Marcar como Leída')
                    ->icon('heroicon-o-envelope-open')
                    ->color('primary')
                    ->visible(fn(DatabaseNotification $record): bool => is_null($record->read_at)) 
                    ->action(function (DatabaseNotification $record) {
                        $record->markAsRead();
                        Notification::make()
                            ->title('Notificación marcada como leída.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('markAsUnread')
                    ->label('Marcar como No Leída')
                    ->icon('heroicon-o-envelope')
                    ->color('secondary')
                    ->visible(fn(DatabaseNotification $record): bool => !is_null($record->read_at)) 
                    ->action(function (DatabaseNotification $record) {
                        $record->markAsUnread();
                        Notification::make()
                            ->title('Notificación marcada como no leída.')
                            ->info()
                            ->send();
                    }),
                //
                // Tables\Actions\Action::make('viewLink')
                //     ->label('Ver Detalles')
                //     ->icon('heroicon-o-arrow-top-right-on-square')
                //     ->color('gray')
                //     ->url(fn(DatabaseNotification $record): string => $record->data['link'] ?? '#')
                //     ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markSelectedAsRead')
                        ->label('Marcar seleccionadas como leídas')
                        ->icon('heroicon-o-envelope-open')
                        ->action(function (Tables\Actions\BulkAction $action, \Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->markAsRead();
                            Notification::make()
                                ->title('Notificaciones seleccionadas marcadas como leídas.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('markSelectedAsUnread')
                        ->label('Marcar seleccionadas como no leídas')
                        ->icon('heroicon-o-envelope')
                        ->action(function (Tables\Actions\BulkAction $action, \Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->markAsUnread();
                            Notification::make()
                                ->title('Notificaciones seleccionadas marcadas como no leídas.')
                                ->info()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No tienes notificaciones.')
            ->emptyStateDescription('Cuando haya actualizaciones importantes, las verás aquí.');
    }

    public static function getRelations(): array
    {
        return [
         
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
           
            // 'create' => Pages\CreateNotification::route('/create'),
            // 'edit' => Pages\EditNotification::route('/{record}/edit'),

        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    
    public static function canEdit($record): bool
    {
        return false;
    }

    
    public static function canDelete($record): bool
    {
        return false;
    }

  
    public static function canView($record): bool
    {
        
        return false;
    }
}
