<?php

namespace App\Filament\Imports;

use App\Models\Order;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Support\Number;

class OrderImporter extends Importer
{
    protected static ?string $model = Order::class;

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
            ImportColumn::make('distributor')
                ->label(__('messages.order.distributor'))
                ->relationship(),
            ImportColumn::make('order_number')
                ->label(__('messages.order.order_number'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('status')
                ->label(__('messages.order.status'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('total_amount')
                ->label(__('messages.order.total_amount'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('order_date')
                ->label(__('messages.order.order_date'))
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('delivery_date')
                ->label(__('messages.order.delivery_date'))
                ->rules(['date']),
            ImportColumn::make('notes')
                ->label(__('messages.order.notes')),
        ];
    }

    public function resolveRecord(): Order
    {
        $order = new Order();

        // Use stored tenant ID instead of Filament::getTenant()
        if ($this->tenantId) {
            $order->store_id = $this->tenantId;
        } else {
            // Throw error if tenant ID is not available
            throw new \Exception('Tenant context not available for import');
        }

        return $order;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $rows = $import->successful_rows === 1 ? 'Zeile' : 'Zeilen';
        $body = 'Bestellungsimport abgeschlossen: ' . Number::format($import->successful_rows) . ' ' . $rows . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $failedRows = $failedRowsCount === 1 ? 'Zeile' : 'Zeilen';
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . $failedRows . ' fehlgeschlagen.';
        }

        return $body;
    }
}
