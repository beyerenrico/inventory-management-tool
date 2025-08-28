<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label(__('messages.product.name')),
            ExportColumn::make('sku')
                ->label(__('messages.product.sku')),
            ExportColumn::make('ean')
                ->label(__('messages.product.ean')),
            ExportColumn::make('description')
                ->label(__('messages.product.description')),
            ExportColumn::make('price')
                ->label(__('messages.product.price'))
                ->formatStateUsing(fn ($state): string => '€' . Number::format($state, 2)),
            ExportColumn::make('stock_quantity')
                ->label(__('messages.product.stock_quantity')),
            ExportColumn::make('created_at')
                ->label(__('messages.product.created_at')),
            ExportColumn::make('updated_at')
                ->label(__('messages.product.updated_at')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $rows = $export->successful_rows === 1 ? 'Zeile' : 'Zeilen';
        $body = 'Produktexport abgeschlossen: ' . Number::format($export->successful_rows) . ' ' . $rows . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $failedRows = $failedRowsCount === 1 ? 'Zeile' : 'Zeilen';
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . $failedRows . ' fehlgeschlagen.';
        }

        return $body;
    }
}
