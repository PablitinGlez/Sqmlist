<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Property;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PropertiesMonthlyChart extends ChartWidget
{
    protected static ?string $heading = 'Propiedades Publicadas por Mes';


    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $months = [];
        $data = [];

        // aki obtengo los 6 meses 
        for ($i = 5; $i >= 0; $i--) {
           
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Property::whereYear('published_at', $date->year)
                ->whereMonth('published_at', $date->month)
                ->where('status', 'published')
                ->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Propiedades Publicadas',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

   
    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
        ];
    }

    protected static ?int $sort = 2;
}
