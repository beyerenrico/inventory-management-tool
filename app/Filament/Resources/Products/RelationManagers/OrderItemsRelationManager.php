<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $title = 'Bestellhistorie';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label(__('messages.order.order_number'))
                    ->searchable()
                    ->url(fn ($record) => "/admin/orders/{$record->order->id}/edit"),
                Tables\Columns\TextColumn::make('order.distributor.name')
                    ->label(__('messages.distributor.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('messages.order.quantity'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('messages.order.unit_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('messages.order.total_price'))
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('order.order_date')
                    ->label(__('messages.order.order_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.status')
                    ->label(__('messages.order.status'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? __("messages.order.status_{$state}") : 'N/A')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->emptyStateHeading(__('messages.widgets.order_history.empty_title'))
            ->emptyStateDescription(__('messages.widgets.order_history.empty_description'))
            ->defaultSort('order.order_date', 'desc');
    }
}
