<?php

namespace App\Filament\Admin\Resources\UserApplicationResource\Pages;

use App\Filament\Admin\Resources\UserApplicationResource; 
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab; 
use Illuminate\Database\Eloquent\Builder;
use App\Models\UserApplication; 

class ListUserApplications extends ListRecords
{
    protected static string $resource = UserApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
          
        ];
    }

    /**
     * Define the tabs for filtering the records.
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todas')
                ->badge(fn() => $this->getModel()::count()), 
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
            
        ];
    }
}
