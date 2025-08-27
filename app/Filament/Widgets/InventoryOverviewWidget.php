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
            Stat::make('Total Products', $totalProducts)
                ->description('All products in catalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            
            Stat::make('Inventory Value', '€' . number_format($totalInventoryValue, 2))
                ->description('Total stock value')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'),
            
            Stat::make('Low Stock Items', $lowStockCount)
                ->description('Less than 10 units')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'warning' : 'success'),
            
            Stat::make('Out of Stock', $outOfStockCount)
                ->description('Items needing reorder')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($outOfStockCount > 0 ? 'danger' : 'success'),
        ];
    }
}
