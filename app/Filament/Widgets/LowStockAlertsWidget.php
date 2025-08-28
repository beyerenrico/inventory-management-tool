<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LowStockAlertsWidget extends TableWidget
{
    public function getTableHeading(): ?string
    {
        return __('messages.widgets.low_stock_alerts.title');
    }
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('stock_quantity', '<=', 10)
                    ->orderBy('stock_quantity', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.product.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label(__('messages.product.sku'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label(__('messages.product.stock'))
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('messages.product.price'))
                    ->money('EUR'),
            ])
            ->actions([
                Action::make('reorder')
                    ->label(__('messages.actions.reorder'))
                    ->icon('heroicon-m-shopping-cart')
                    ->url(fn (Product $record): string => '/admin/orders/create')
                    ->color('primary'),
            ])
            ->emptyStateHeading(__('messages.widgets.low_stock_alerts.empty_title'))
            ->emptyStateDescription(__('messages.widgets.low_stock_alerts.empty_description'))
            ->emptyStateIcon('heroicon-o-check-circle')
            ->defaultPaginationPageOption(5);
    }
}
