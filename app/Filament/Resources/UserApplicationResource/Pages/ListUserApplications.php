<?php

namespace App\Filament\Resources\UserApplicationResource\Pages;

use App\Filament\Resources\UserApplicationResource; // Correct Resource reference
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab; // Don't forget to import Tab
use Illuminate\Database\Eloquent\Builder;
use App\Models\UserApplication; // Import the UserApplication model to use its constants

class ListUserApplications extends ListRecords
{
    protected static string $resource = UserApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Applications are created from the frontend, so no 'CreateAction' is needed here.
            // Actions\CreateAction::make(), // Remove or comment this out
        ];
    }

    /**
     * Define the tabs for filtering the records.
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todas')
                ->badge(fn() => $this->getModel()::count()), // Shows count for all applications
            'pending' => Tab::make('Pendientes')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', UserApplication::STATUS_PENDING))
                ->badge(fn() => $this->getModel()::where('status', UserApplication::STATUS_PENDING)->count())
                ->badgeColor('warning'),
            'approved' => Tab::make('Aprobadas')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', UserApplication::STATUS_APPROVED))
                ->badge(fn() => $this->getModel()::where('status', UserApplication::STATUS_APPROVED)->count())
                ->badgeColor('success'),
            'rejected' => Tab::make('Rechazadas')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', UserApplication::STATUS_REJECTED))
                ->badge(fn() => $this->getModel()::where('status', UserApplication::STATUS_REJECTED)->count())
                ->badgeColor('danger'),
            // You could also add tabs for specific user types if desired, for example:
            // 'agents' => Tab::make('Agentes')
            //     ->modifyQueryUsing(fn (Builder $query) => $query->where('requested_user_type', UserApplication::TYPE_AGENT))
            //     ->badge(fn () => $this->getModel()::where('requested_user_type', UserApplication::TYPE_AGENT)->count())
            //     ->badgeColor('info'),
        ];
    }
}
