<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use App\Models\Skema;
use App\Models\Peserta;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    // protected int | string | array $columnSpan = 'full';
    // protected function getcolumns(): int
    // {
    //     return 1;
    // }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Skema Sertifikasi', Skema::count())
                ->description('Jumlah skema aktif')
                ->color('primary'),
            Stat::make('Total Peserta Terdaftar', Peserta::count())
                ->description('Jumlah peserta terintegrasi')
                ->color('success'),
        ];
    }
}
