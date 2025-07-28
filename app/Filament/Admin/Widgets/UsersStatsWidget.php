<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
  
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $suspendedUsers = User::whereNotNull('suspended_at')->count();

        
        $todayUsers = User::whereDate('created_at', today())->count();

      
        $weekUsers = User::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        return [
            Stat::make('Total de Usuarios', $totalUsers)
                ->description('Usuarios registrados en total')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

          
            Stat::make('Registros Hoy', $todayUsers)
                ->description('Nuevos usuarios hoy')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Registros Esta Semana', $weekUsers)
                ->description('Nuevos usuarios esta semana')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('gray'),
        ];
    }

    protected static ?int $sort = 1;
}
