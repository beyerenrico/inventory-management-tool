<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlySpendingChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Spending';
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(function ($monthsBack) {
            return Carbon::now()->subMonths($monthsBack);
        });

        $data = $months->map(function (Carbon $month) {
            $total = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
            
            return [
                'month' => $month->format('M Y'),
                'total' => (float) $total,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Spending (€)',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ]
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "€" + value.toLocaleString(); }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "€" + context.parsed.y.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}
