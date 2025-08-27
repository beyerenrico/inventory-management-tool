<?php

namespace App\Filament\Imports;

use App\Models\Distributor;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DistributorImport extends Importer
{
    protected static ?string $model = Distributor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('company')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('email')
                ->rules(['nullable', 'email', 'max:255']),
            ImportColumn::make('website')
                ->rules(['nullable', 'url', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('address')
                ->rules(['nullable', 'max:65535']),
            ImportColumn::make('notes')
                ->rules(['nullable', 'max:65535']),
        ];
    }

    public function resolveRecord(): ?Distributor
    {
        return Distributor::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your distributor import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}