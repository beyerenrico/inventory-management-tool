<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ProductOrderHistoryWidget extends TableWidget
{
    protected static ?string $heading = 'Order History';

    protected int | string | array $columnSpan = 'full';

    public ?int $productId = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->with(['order.distributor'])
                    ->when($this->productId, fn ($query) => $query->where('product_id', $this->productId))
                    ->latest('created_at')
            )
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->url(fn ($record) => "/admin/orders/{$record->order->id}/edit"),
                TextColumn::make('order.distributor.name')
                    ->label('Distributor')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric(),
                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('EUR'),
                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('EUR'),
                TextColumn::make('order.order_date')
                    ->label('Order Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('order.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
            ])
            ->emptyStateHeading('No Orders Found')
            ->emptyStateDescription('This product has not been ordered yet.')
            ->defaultSort('order.order_date', 'desc');
    }
}
