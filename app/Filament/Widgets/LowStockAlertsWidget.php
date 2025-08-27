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
    protected static ?string $heading = 'Low Stock Alerts';
    
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR'),
            ])
            ->actions([
                Action::make('reorder')
                    ->label('Reorder')
                    ->icon('heroicon-m-shopping-cart')
                    ->url(fn (Product $record): string => '/admin/orders/create')
                    ->color('primary'),
            ])
            ->emptyStateHeading('No Low Stock Items')
            ->emptyStateDescription('All products have adequate stock levels.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->defaultPaginationPageOption(5);
    }
}
