<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ChartTest extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static string $color = 'info';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = true;
    protected function getData(): array
    {
        $data = Trend::model(User::class)
        ->between(
            start: now()->subMonths(3),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
        return [

            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' =>$data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        // can change this type 
        return 'line';
    }
}
