<?php

namespace App\Filament\Admin\Widgets;

use App\Models\UserApplication;
use Filament\Widgets\ChartWidget;

class UserTypesChart extends ChartWidget
{
    protected static ?string $heading = 'Distribución de Tipos de Usuario';


    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $ownerCount = UserApplication::where('requested_user_type', 'owner')
            ->where('status', 'approved')->count();

        $agentCount = UserApplication::where('requested_user_type', 'agent')
            ->where('status', 'approved')->count();

        $companyCount = UserApplication::where('requested_user_type', 'real_estate_company')
            ->where('status', 'approved')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tipos de Usuario',
                    'data' => [$ownerCount, $agentCount, $companyCount],
                    'backgroundColor' => [
                        '#10B981',
                        '#3B82F6',
                        '#8B5CF6', 
                    ],
                    'borderColor' => [
                        '#059669',
                        '#2563EB',
                        '#7C3AED',
                    ],
                ],
            ],
            'labels' => ['Dueños Directos', 'Agentes', 'Inmobiliarias'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }


    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected static ?int $sort = 3;
}
