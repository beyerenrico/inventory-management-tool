<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ProductOrderHistoryWidget extends TableWidget
{
    public function getTableHeading(): ?string
    {
        return __('messages.widgets.order_history.title');
    }

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
                    ->label(__('messages.widgets.order_history.order_number'))
                    ->searchable()
                    ->url(fn ($record) => "/admin/orders/{$record->order->id}/edit"),
                TextColumn::make('order.distributor.name')
                    ->label(__('messages.distributor.name'))
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label(__('messages.widgets.order_history.quantity'))
                    ->numeric(),
                TextColumn::make('unit_price')
                    ->label(__('messages.widgets.order_history.unit_price'))
                    ->money('EUR'),
                TextColumn::make('total_price')
                    ->label(__('messages.widgets.order_history.total_price'))
                    ->money('EUR'),
                TextColumn::make('order.order_date')
                    ->label(__('messages.widgets.order_history.order_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('order.status')
                    ->label(__('messages.widgets.order_history.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("messages.order.status_{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
            ])
            ->emptyStateHeading(__('messages.widgets.order_history.empty_title'))
            ->emptyStateDescription(__('messages.widgets.order_history.empty_description'))
            ->defaultSort('order.order_date', 'desc');
    }
}
