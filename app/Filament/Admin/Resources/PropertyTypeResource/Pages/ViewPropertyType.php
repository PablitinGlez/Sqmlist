<?php

namespace App\Filament\Admin\Resources\PropertyTypeResource\Pages;

use App\Filament\Admin\Resources\PropertyTypeResource;
use App\Models\PropertyType;
use App\Models\FeatureSection;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewPropertyType extends ViewRecord
{
    protected static string $resource = PropertyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información Básica')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nombre'),

                        Infolists\Components\TextEntry::make('category.name')
                            ->label('Categoría')
                            ->badge()
                            ->color(fn(PropertyType $record): string => $record->category->color ?? 'gray'),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Descripción')
                            ->placeholder('Sin descripción'),

                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Estado')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-mark')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Características Asignadas')
                    ->schema($this->getFeaturesInfolistSchema())
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    protected function getFeaturesInfolistSchema(): array
    {
        $propertyType = $this->getRecord();
        $assignedFeatures = $propertyType->features()->pluck('features.id')->toArray();

        if (empty($assignedFeatures)) {
            return [
                Infolists\Components\TextEntry::make('no_features')
                    ->label('')
                    ->state('No hay características asignadas a este tipo de propiedad')
                    ->color('warning'),
            ];
        }

        $sectionsWithFeatures = FeatureSection::with(['features' => function ($query) use ($assignedFeatures) {
            $query->whereIn('features.id', $assignedFeatures)->orderBy('order');
        }])
            ->whereHas('features', function ($query) use ($assignedFeatures) {
                $query->whereIn('features.id', $assignedFeatures);
            })
            ->ordered()
            ->get();

        $schema = [];

        foreach ($sectionsWithFeatures as $section) {
            if ($section->features->isNotEmpty()) {
                $featuresText = $section->features->pluck('name')->join(', ');

                $schema[] = Infolists\Components\TextEntry::make("section_{$section->id}")
                    ->label($section->name)
                    ->state($featuresText)
                    ->listWithLineBreaks()
                    ->bulleted();
            }
        }

        return $schema;
    }
}
