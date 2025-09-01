<?php

namespace App\Filament\Monitor\Widgets;

use App\Models\ClassModel;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MonitorKpis extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $ids = $user?->monitoredClasses()->pluck('classes.id') ?? collect();
        $today = now()->toDateString();

        $ongoing = ClassModel::whereIn('id', $ids)
            ->where('status', 'ongoing')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();

        $startingThisWeek = ClassModel::whereIn('id', $ids)
            ->whereDate('start_date', '>=', $weekStart)
            ->whereDate('start_date', '<=', $weekEnd)
            ->count();

        $endingThisWeek = ClassModel::whereIn('id', $ids)
            ->whereDate('end_date', '>=', $weekStart)
            ->whereDate('end_date', '<=', $weekEnd)
            ->count();

        return [
            Stat::make('Turmas em andamento hoje', $ongoing),
            Stat::make('Iniciando nesta semana', $startingThisWeek),
            Stat::make('Finalizando nesta semana', $endingThisWeek),
        ];
    }
}
