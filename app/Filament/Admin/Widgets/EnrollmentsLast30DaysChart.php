<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class EnrollmentsLast30DaysChart extends ChartWidget
{
    protected ?string $heading = 'Enrollments - Last 30 Days';
    protected ?string $maxHeight = '300px';
    protected ?string $pollingInterval = null;

    protected function getData(): array
    {
        $from = now()->subDays(30)->startOfDay();

        $rows = Enrollment::query()
            ->selectRaw('DATE(enrolled_at) as day, COUNT(*) as total')
            ->where('enrolled_at', '>=', $from)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->all();

        $labels = [];
        $data = [];

        for ($d = 30; $d >= 0; $d--) {
            $day = now()->subDays($d)->toDateString();
            $labels[] = $day;
            $data[] = (int)($rows[$day] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => __('Enrollments per day'),
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
