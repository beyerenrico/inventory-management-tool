<?php

namespace App\Filament\Imports;

use App\Models\Order;
use App\Models\Distributor;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class OrderImport extends Importer
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('order_number')
                ->requiredMapping()
                ->rules(['required', 'max:255', 'unique:orders,order_number']),
            ImportColumn::make('distributor_email')
                ->label('Distributor Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'exists:distributors,email']),
            ImportColumn::make('status')
                ->rules(['required', 'in:pending,processing,shipped,delivered,cancelled'])
                ->default('pending'),
            ImportColumn::make('order_date')
                ->rules(['required', 'date']),
            ImportColumn::make('delivery_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('notes')
                ->rules(['nullable', 'max:65535']),
        ];
    }

    public function resolveRecord(): ?Order
    {
        // Find distributor by email
        $distributor = Distributor::where('email', $this->data['distributor_email'])->first();
        
        if (!$distributor) {
            return null;
        }

        return Order::firstOrNew([
            'order_number' => $this->data['order_number'],
        ], [
            'distributor_id' => $distributor->id,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}