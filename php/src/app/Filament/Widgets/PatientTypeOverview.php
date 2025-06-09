<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Patient;

class PatientTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Cats', Patient::query()->where('type', 'cat')->count()),
            Stat::make('Dogs', Patient::query()->where('type', 'dog')->count()),
            Stat::make('Rabbits', Patient::query()->where('type', 'rabbit')->count()),
            Stat::make('snake', Patient::query()->where('type', 'snake')->count()),
            Stat::make('hamster', Patient::query()->where('type', 'hamster')->count()),
            Stat::make('bird', Patient::query()->where('type', 'bird')->count()),
        ];
    }
}
