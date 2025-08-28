<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalInventoryValue = Product::sum(\DB::raw('price * stock_quantity'));
        $lowStockCount = Product::where('stock_quantity', '<', 10)->count();
        $outOfStockCount = Product::where('stock_quantity', 0)->count();

        return [
            Stat::make(__('messages.widgets.inventory_overview.total_products'), $totalProducts)
                ->description(__('messages.widgets.inventory_overview.total_products_desc'))
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            
            Stat::make(__('messages.widgets.inventory_overview.inventory_value'), '€' . number_format($totalInventoryValue, 2))
                ->description(__('messages.widgets.inventory_overview.inventory_value_desc'))
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'),
            
            Stat::make(__('messages.widgets.inventory_overview.low_stock_items'), $lowStockCount)
                ->description(__('messages.widgets.inventory_overview.low_stock_items_desc'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'warning' : 'success'),
            
            Stat::make(__('messages.widgets.inventory_overview.out_of_stock'), $outOfStockCount)
                ->description(__('messages.widgets.inventory_overview.out_of_stock_desc'))
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($outOfStockCount > 0 ? 'danger' : 'success'),
        ];
    }
}
