<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Support\Number;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    protected ?int $tenantId = null;

    public function __construct($import, $columnMap, $options)
    {
        parent::__construct($import, $columnMap, $options);

        // Store the tenant ID when the importer is instantiated (during web request)
        $tenant = Filament::getTenant();
        if ($tenant) {
            $this->tenantId = $tenant->id;
        }
    }

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
        $product = new Product();

        // Use stored tenant ID instead of Filament::getTenant()
        if ($this->tenantId) {
            $product->store_id = $this->tenantId;
        } else {
            // Throw error if tenant ID is not available
            throw new \Exception('Tenant context not available for import');
        }

        return $product;
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
