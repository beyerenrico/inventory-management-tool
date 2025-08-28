<?php

namespace App\Filament\Exports;

use App\Models\Distributor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DistributorExporter extends Exporter
{
    protected static ?string $model = Distributor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label(__('messages.distributor.name')),
            ExportColumn::make('company')
                ->label(__('messages.distributor.company')),
            ExportColumn::make('email')
                ->label(__('messages.distributor.email')),
            ExportColumn::make('website')
                ->label(__('messages.distributor.website')),
            ExportColumn::make('phone')
                ->label(__('messages.distributor.phone')),
            ExportColumn::make('address')
                ->label(__('messages.distributor.address')),
            ExportColumn::make('notes')
                ->label(__('messages.distributor.notes')),
            ExportColumn::make('created_at')
                ->label(__('messages.distributor.created_at')),
            ExportColumn::make('updated_at')
                ->label(__('messages.distributor.updated_at')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $rows = $export->successful_rows === 1 ? 'Zeile' : 'Zeilen';
        $body = 'Händlerexport abgeschlossen: ' . Number::format($export->successful_rows) . ' ' . $rows . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $failedRows = $failedRowsCount === 1 ? 'Zeile' : 'Zeilen';
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . $failedRows . ' fehlgeschlagen.';
        }

        return $body;
    }
}
