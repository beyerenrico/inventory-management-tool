<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RecentOrdersWidget extends TableWidget
{
    public function getTableHeading(): ?string
    {
        return __('messages.widgets.recent_orders.title');
    }

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with('distributor')
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label(__('messages.order.order_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('distributor.name')
                    ->label(__('messages.distributor.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('messages.order.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("messages.order.status_{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('messages.order.total_amount'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->label(__('messages.order.order_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.widgets.recent_orders.created_label'))
                    ->since()
                    ->toggleable(),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('messages.actions.view'))
                    ->icon('heroicon-m-eye')
                    ->url(fn (Order $record): string => "/admin/orders/{$record->id}/edit")
                    ->color('primary'),
            ])
            ->emptyStateHeading(__('messages.widgets.recent_orders.empty_title'))
            ->emptyStateDescription(__('messages.widgets.recent_orders.empty_description'))
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->defaultPaginationPageOption(5);
    }
}
