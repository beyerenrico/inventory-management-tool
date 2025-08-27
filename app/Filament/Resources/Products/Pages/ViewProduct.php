<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Product Information')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Product Image')
                            ->defaultImageUrl('/images/placeholder-product.png')
                            ->columnSpanFull(),
                        TextEntry::make('name')
                            ->label('Product Name'),
                        TextEntry::make('sku')
                            ->label('SKU'),
                        TextEntry::make('ean')
                            ->label('EAN/GTIN'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('current_price')
                            ->label('Current Price')
                            ->money('EUR')
                            ->getStateUsing(fn ($record) => $record->price),
                        TextEntry::make('average_price')
                            ->label('Average Order Price')
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $avgPrice = $record->orderItems()->avg('unit_price');
                                return $avgPrice ? $avgPrice : $record->price;
                            }),
                        TextEntry::make('stock_quantity')
                            ->label('Current Stock')
                            ->badge()
                            ->color(fn ($state) => $state < 10 ? Color::Red : ($state < 50 ? Color::Yellow : Color::Green)),
                        TextEntry::make('total_ordered')
                            ->label('Total Quantity Ordered')
                            ->getStateUsing(fn ($record) => $record->orderItems()->sum('quantity')),
                    ])
                    ->columns(3),

                Section::make('Price Analysis')
                    ->schema([
                        TextEntry::make('min_price')
                            ->label('Lowest Order Price')
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $minPrice = $record->orderItems()->min('unit_price');
                                return $minPrice ?? $record->price;
                            }),
                        TextEntry::make('max_price')
                            ->label('Highest Order Price')
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $maxPrice = $record->orderItems()->max('unit_price');
                                return $maxPrice ?? $record->price;
                            }),
                        TextEntry::make('price_variance')
                            ->label('Price Variance')
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $minPrice = $record->orderItems()->min('unit_price');
                                $maxPrice = $record->orderItems()->max('unit_price');
                                if ($minPrice && $maxPrice) {
                                    return $maxPrice - $minPrice;
                                }
                                return 0;
                            }),
                        TextEntry::make('total_orders')
                            ->label('Total Orders')
                            ->getStateUsing(fn ($record) => $record->orderItems()->count()),
                        TextEntry::make('unique_distributors')
                            ->label('Different Distributors')
                            ->getStateUsing(function ($record) {
                                return $record->orderItems()
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->distinct('orders.distributor_id')
                                    ->count('orders.distributor_id');
                            }),
                        TextEntry::make('last_order_date')
                            ->label('Last Ordered')
                            ->getStateUsing(function ($record) {
                                $lastOrder = $record->orderItems()
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->orderBy('orders.order_date', 'desc')
                                    ->first(['orders.order_date']);
                                return $lastOrder ? \Carbon\Carbon::parse($lastOrder->order_date)->format('M d, Y') : 'Never';
                            }),
                    ])
                    ->columns(3),
            ]);
    }

}
