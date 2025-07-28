<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Property;
use App\Models\PropertyType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropertiesStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
       
        $totalProperties = Property::count();
        $publishedProperties = Property::where('status', 'published')->count();
        $draftProperties = Property::where('status', 'draft')->count();
        $pendingProperties = Property::where('status', 'pending')->count();

      
        $saleProperties = Property::where('operation_type', 'sale')
            ->where('status', 'published')->count();
        $rentProperties = Property::where('operation_type', 'rent')
            ->where('status', 'published')->count();
        $bothProperties = Property::where('operation_type', 'both')
            ->where('status', 'published')->count();

        
        $todayProperties = Property::whereDate('published_at', today())->count();

        
        $weekProperties = Property::whereBetween('published_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        return [
          

            Stat::make('Propiedades Publicadas', $publishedProperties)
                ->description('Propiedades visibles al público')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            

            Stat::make('En Venta', $saleProperties)
                ->description('Propiedades para venta')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('green'),

            Stat::make('En Renta', $rentProperties)
                ->description('Propiedades para renta')
                ->descriptionIcon('heroicon-m-key')
                ->color('blue'),

            Stat::make('Venta y Renta', $bothProperties)
                ->description('Propiedades para ambos')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('purple'),

            Stat::make('Publicadas Hoy', $todayProperties)
                ->description('Nuevas propiedades hoy')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

           
        ];
    }

    protected static ?int $sort = 4;
}
