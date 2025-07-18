<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Support\Colors\Color;

/**
 * Recurso de Filament para la gestión de Categorías.
 * Permite a los administradores crear, editar, visualizar y eliminar categorías de propiedades
 * dentro del panel de administración de Filament.
 */
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Categorías';

    protected static ?string $modelLabel = 'Categoría';

    protected static ?string $pluralModelLabel = 'Categorías';

    protected static ?string $navigationGroup = 'Gestión de Propiedades';

    protected static ?int $navigationSort = 1;

   
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de la Categoría')
                    ->description('Complete los datos de la categoría')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ej: Residencial, Comercial, Industrial...')
                            ->live(onBlur: true),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Color de la etiqueta')
                            ->default('#6B7280')
                            ->hex()
                            ->helperText('Seleccione un color para identificar visualmente esta categoría')
                            ->rules(function (Forms\Components\ColorPicker $component) {
                                return ['unique:categories,color'];
                            }),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->placeholder('Descripción opcional de la categoría...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->html()
                    ->formatStateUsing(function (string $state, Category $record): string {
                        $hex = ltrim($record->color, '#');
                        $r = hexdec(substr($hex, 0, 2));
                        $g = hexdec(substr($hex, 2, 2));
                        $b = hexdec(substr($hex, 4, 2));
                        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
                        $textColor = $brightness > 128 ? '#000000' : '#FFFFFF';

                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: ' . $record->color . '; color: ' . $textColor . '">' . $state . '</span>';
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->placeholder('Sin descripción'),

                Tables\Columns\TextColumn::make('property_types_count')
                    ->label('Tipos de Propiedad')
                    ->counts('propertyTypes')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
          
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Category $record) {
                        if ($record->propertyTypes()->count() > 0) {
                            throw new \Exception('No se puede eliminar esta categoría porque tiene tipos de propiedad asociados.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
