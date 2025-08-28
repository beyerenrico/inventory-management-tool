<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label(__('messages.product.name'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('sku')
                ->label(__('messages.product.sku'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('ean')
                ->label(__('messages.product.ean'))
                ->rules(['max:255']),
            ImportColumn::make('description')
                ->label(__('messages.product.description')),
            ImportColumn::make('image')
                ->label(__('messages.product.image'))
                ->rules(['max:255']),
            ImportColumn::make('price')
                ->label(__('messages.product.price'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('stock_quantity')
                ->label(__('messages.product.stock_quantity'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): Product
    {
        return new Product();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $rows = $import->successful_rows === 1 ? 'Zeile' : 'Zeilen';
        $body = 'Produktimport abgeschlossen: ' . Number::format($import->successful_rows) . ' ' . $rows . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $failedRows = $failedRowsCount === 1 ? 'Zeile' : 'Zeilen';
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . $failedRows . ' fehlgeschlagen.';
        }

        return $body;
    }
}
