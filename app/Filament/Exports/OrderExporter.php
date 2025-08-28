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
                ->label(__('messages.order.order_number')),
            ExportColumn::make('distributor.name')
                ->label(__('messages.distributor.name')),
            ExportColumn::make('status')
                ->label(__('messages.order.status'))
                ->formatStateUsing(fn ($state): string => __('messages.order.status_' . $state)),
            ExportColumn::make('order_date')
                ->label(__('messages.order.order_date')),
            ExportColumn::make('delivery_date')
                ->label(__('messages.order.delivery_date')),
            ExportColumn::make('total_amount')
                ->label(__('messages.order.total_amount'))
                ->formatStateUsing(fn ($state): string => '€' . Number::format($state, 2)),
            ExportColumn::make('notes')
                ->label(__('messages.order.notes')),
            ExportColumn::make('created_at')
                ->label(__('messages.order.created_at')),
            ExportColumn::make('updated_at')
                ->label(__('messages.order.updated_at')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $rows = $export->successful_rows === 1 ? 'Zeile' : 'Zeilen';
        $body = 'Bestellungsexport abgeschlossen: ' . Number::format($export->successful_rows) . ' ' . $rows . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $failedRows = $failedRowsCount === 1 ? 'Zeile' : 'Zeilen';
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . $failedRows . ' fehlgeschlagen.';
        }

        return $body;
    }
}
