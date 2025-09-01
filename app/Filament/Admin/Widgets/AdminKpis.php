<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminKpis extends StatsOverviewWidget
{
    protected ?string $heading = 'Visão Operacional';
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $today = now()->toDateString();

        $classesInProgress = ClassModel::query()
            ->where('status', 'ongoing')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $concluded = Enrollment::query()->where('status', 'completed')->count();
        $active = Enrollment::query()->where('status', 'active')->count();

        $conclusionRate = ($concluded + $active) > 0
            ? round(($concluded / ($concluded + $active)) * 100, 1)
            : 0;

        $last30Enrollments = Enrollment::where('enrolled_at', '>=', now()->subDays(30))->count();
        $prev30Enrollments = Enrollment::whereBetween('enrolled_at', [now()->subDays(60), now()->subDays(30)])->count();
        $diff = $last30Enrollments - $prev30Enrollments;
        $trendText = ($prev30Enrollments > 0)
            ? ($diff >= 0 ? '+' . $diff : (string)$diff) . ' vs período anterior'
            : 'sem histórico';
        $trendIcon = $diff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $trendColor = $diff >= 0 ? 'success' : 'danger';

        $newStudents30 = Student::where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('Total de Alunos', Student::count()),
            Stat::make('Novos Alunos (30 dias)', $newStudents30),
            Stat::make('Aulas em Andamento', number_format($classesInProgress)),
            Stat::make('Total de Turmas', ClassModel::count()),
            Stat::make('Matrículas (30 dias)', $last30Enrollments)
                ->description($trendText)
                ->descriptionIcon($trendIcon)
                ->color($trendColor),
            Stat::make('Taxa de Conclusão', $conclusionRate . '%'),
        ];
    }
}
