<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;

class ProductPriceHistoryChart extends ChartWidget
{
    protected ?string $heading = null;
    
    public function getHeading(): string
    {
        return __('messages.widgets.price_history.title');
    }

    protected int | string | array $columnSpan = 'full';

    public ?int $productId = null;

    public function getDescription(): ?string
    {
        return __('messages.widgets.price_history.description');
    }

    protected function getData(): array
    {
        if (!$this->productId) {
            return ['datasets' => [], 'labels' => []];
        }

        $orderItems = OrderItem::with(['order'])
            ->where('product_id', $this->productId)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->orderBy('orders.order_date')
            ->get(['order_items.*', 'orders.order_date']);

        $labels = [];
        $prices = [];

        foreach ($orderItems as $item) {
            $labels[] = $item->order_date->format('M d, Y');
            $prices[] = $item->unit_price;
        }

        return [
            'datasets' => [
                [
                    'label' => __('messages.widgets.price_history.chart_label'),
                    'data' => $prices,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.1,
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
