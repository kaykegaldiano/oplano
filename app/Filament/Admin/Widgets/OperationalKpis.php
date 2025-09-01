<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalKpis extends StatsOverviewWidget
{
    protected ?string $heading = 'Operational Vision';

    protected function getStats(): array
    {
        $activeStudents = Student::query()->count();
        $today = now()->toDateString();

        $classesInProgress = ClassModel::query()
            ->where('status', 'ongoing')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $enrollments30Days = Enrollment::query()
            ->whereDate('enrolled_at', '>=', now()->subDays(30))
            ->count();

        $concluded = Enrollment::query()->where('status', 'completed')->count();
        $active = Enrollment::query()->where('status', 'active')->count();

        $conclusionRate = ($concluded + $active) > 0
            ? round(($concluded / ($concluded + $active)) * 100, 1)
            : 0;

        return [
            Stat::make('Active Students', number_format($activeStudents)),
            Stat::make('Classes In Progress', number_format($classesInProgress)),
            Stat::make('Enrollments (30 days)', number_format($enrollments30Days)),
            Stat::make('Conclusion Rate', $conclusionRate . '%'),
        ];
    }
}
