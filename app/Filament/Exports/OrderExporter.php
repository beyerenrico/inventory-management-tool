<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('order_number')
                ->label('Order Number'),
            ExportColumn::make('distributor.name')
                ->label('Distributor'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state): string => ucfirst($state)),
            ExportColumn::make('order_date')
                ->label('Order Date'),
            ExportColumn::make('delivery_date')
                ->label('Delivery Date'),
            ExportColumn::make('total_amount')
                ->label('Total Amount (€)')
                ->formatStateUsing(fn ($state): string => '€' . Number::format($state, 2)),
            ExportColumn::make('notes')
                ->label('Notes'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
