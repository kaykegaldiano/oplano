<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class AdminEnrollmentsChart extends ChartWidget
{
    protected ?string $heading = 'Matrículas - Últimos 30 Dias';
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

        $movingAvg = [];
        $window = 7;
        $sum = 0;
        foreach ($data as $i => $iValue) {
            $sum += $iValue;
            if ($i >= $window) {
                $sum -= $data[$i - $window];
                $avg = $sum / $window;
            } else {
                $avg = $sum / ($i + 1);
            }
            $movingAvg[] = round($avg, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => __('Matrículas por dia'),
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => false,
                    'tension' => 0.25,
                ],
                [
                    'label' => __('Média móvel (7d)'),
                    'data' => $movingAvg,
                    'borderColor' => '#10b981',
                    'borderDash' => [6, 6],
                    'fill' => false,
                    'pointRadius' => 0,
                    'tension' => 0.25,
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
