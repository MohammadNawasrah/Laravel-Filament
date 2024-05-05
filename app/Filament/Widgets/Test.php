<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Test extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("New Users" , User::count())
            ->icon("heroicon-m-user-group")
            ->description("New Users that have joined")
            ->descriptionIcon("heroicon-m-users")
            ->chart([1,3,7,10,40,70,10,50,32,10])
            ->color("info")
    
        ];
    }
}
