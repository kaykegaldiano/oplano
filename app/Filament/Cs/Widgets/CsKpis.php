<?php

namespace App\Filament\Cs\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CsKpis extends StatsOverviewWidget
{
    protected ?string $heading = 'Visão Operacional';
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $pending = Enrollment::where('status', 'pending')->count();
        $active = Enrollment::where('status', 'active')->count();
        $completed = Enrollment::where('status', 'completed')->count();

        $enrollLast30 = Enrollment::where('enrolled_at', '>=', now()->subDays(30))->count();
        $oldPending7 = Enrollment::where('status', 'pending')
            ->where('created_at', '<=', now()->subDays(7))
            ->count();

        return [
            Stat::make('Pendentes', $pending),
            Stat::make('Ativas', $active),
            Stat::make('Concluídas', $completed),
            Stat::make('Matrículas (30 dias)', $enrollLast30),
            Stat::make('Pendentes há 7+ dias', $oldPending7)
                ->color($oldPending7 > 0 ? 'warning' : 'success'),
        ];
    }
}
