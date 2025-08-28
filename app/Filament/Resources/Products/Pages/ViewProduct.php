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
                Section::make(__('messages.product.product_information'))
                    ->schema([
                        ImageEntry::make('image')
                            ->label(__('messages.product.image'))
                            ->defaultImageUrl('/images/placeholder-product.png')
                            ->columnSpanFull(),
                        TextEntry::make('name')
                            ->label(__('messages.product.name'))
                            ->columnSpanFull(),
                        TextEntry::make('sku')
                            ->label(__('messages.product.sku')),
                        TextEntry::make('ean')
                            ->label(__('messages.product.ean')),
                        TextEntry::make('description')
                            ->label(__('messages.product.description'))
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('current_price')
                            ->label(__('messages.product.current_price'))
                            ->money('EUR')
                            ->getStateUsing(fn ($record) => $record->price),
                        TextEntry::make('average_price')
                            ->label(__('messages.product.average_price'))
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $avgPrice = $record->orderItems()->avg('unit_price');
                                return $avgPrice ? $avgPrice : $record->price;
                            }),
                        TextEntry::make('stock_quantity')
                            ->label(__('messages.product.stock_quantity'))
                            ->badge()
                            ->color(fn ($state) => $state < 10 ? Color::Red : ($state < 50 ? Color::Yellow : Color::Green)),
                        TextEntry::make('total_ordered')
                            ->label('Gesamte bestellte Menge')
                            ->getStateUsing(fn ($record) => $record->orderItems()->sum('quantity')),
                    ])
                    ->columns(3),

                Section::make('Preisanalyse')
                    ->schema([
                        TextEntry::make('min_price')
                            ->label('Niedrigster Bestellpreis')
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $minPrice = $record->orderItems()->min('unit_price');
                                return $minPrice ?? $record->price;
                            }),
                        TextEntry::make('max_price')
                            ->label('Höchster Bestellpreis')
                            ->money('EUR')
                            ->getStateUsing(function ($record) {
                                $maxPrice = $record->orderItems()->max('unit_price');
                                return $maxPrice ?? $record->price;
                            }),
                        TextEntry::make('price_variance')
                            ->label('Preisvarianz')
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
                            ->label('Gesamte Bestellungen')
                            ->getStateUsing(fn ($record) => $record->orderItems()->count()),
                        TextEntry::make('unique_distributors')
                            ->label('Verschiedene Händler')
                            ->getStateUsing(function ($record) {
                                return $record->orderItems()
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->distinct('orders.distributor_id')
                                    ->count('orders.distributor_id');
                            }),
                        TextEntry::make('last_order_date')
                            ->label('Zuletzt bestellt')
                            ->getStateUsing(function ($record) {
                                $lastOrder = $record->orderItems()
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->orderBy('orders.order_date', 'desc')
                                    ->first(['orders.order_date']);
                                return $lastOrder ? \Carbon\Carbon::parse($lastOrder->order_date)->format('d.m.Y') : 'Niemals';
                            }),
                    ])
                    ->columns(3),
            ]);
    }

}
