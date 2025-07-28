<?php


namespace App\Filament\Admin\Widgets;

use App\Models\UserApplication;
use App\Models\ProfileDetail;
use App\Models\ProfileDetails;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
      
        $pendingApplications = UserApplication::where('status', 'pending')->count();
        $approvedApplications = UserApplication::where('status', 'approved')->count();
        $rejectedApplications = UserApplication::where('status', 'rejected')->count();

        $ownerProfiles = UserApplication::where('requested_user_type', 'owner')
            ->where('status', 'approved')->count();
        $agentProfiles = UserApplication::where('requested_user_type', 'agent')
            ->where('status', 'approved')->count();
        $companyProfiles = UserApplication::where('requested_user_type', 'real_estate_company')
            ->where('status', 'approved')->count();

        
        $activeProfiles = ProfileDetails::where('status', 'active')->count();

        return [
          

            Stat::make('Dueños Directos', $ownerProfiles)
                ->description('Perfiles de dueños aprobados')
                ->descriptionIcon('heroicon-m-home')
                ->color('info'),

            Stat::make('Agentes', $agentProfiles)
                ->description('Perfiles de agentes aprobados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Inmobiliarias', $companyProfiles)
                ->description('Perfiles de inmobiliarias aprobados')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('gray'),

        
        ];
    }

    protected static ?int $sort = 5;
}
