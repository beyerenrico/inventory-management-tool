<?php

namespace App\Filament\Imports;

use App\Models\Distributor;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Support\Number;

class DistributorImporter extends Importer
{
    protected static ?string $model = Distributor::class;

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
                ->label(__('messages.distributor.name'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label(__('messages.distributor.email'))
                ->rules(['email', 'max:255']),
            ImportColumn::make('website')
                ->label(__('messages.distributor.website'))
                ->rules(['max:255']),
            ImportColumn::make('address')
                ->label(__('messages.distributor.address')),
            ImportColumn::make('company')
                ->label(__('messages.distributor.company'))
                ->rules(['max:255']),
            ImportColumn::make('notes')
                ->label(__('messages.distributor.notes')),
            ImportColumn::make('phone')
                ->label(__('messages.distributor.phone'))
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Distributor
    {
        $distributor = new Distributor();

        // Use stored tenant ID instead of Filament::getTenant()
        if ($this->tenantId) {
            $distributor->store_id = $this->tenantId;
        } else {
            // Throw error if tenant ID is not available
            throw new \Exception('Tenant context not available for import');
        }

        return $distributor;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $rows = $import->successful_rows === 1 ? 'Zeile' : 'Zeilen';
        $body = 'Händlerimport abgeschlossen: ' . Number::format($import->successful_rows) . ' ' . $rows . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $failedRows = $failedRowsCount === 1 ? 'Zeile' : 'Zeilen';
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . $failedRows . ' fehlgeschlagen.';
        }

        return $body;
    }
}
