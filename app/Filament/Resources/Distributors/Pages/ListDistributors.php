<?php

namespace App\Filament\Resources\Distributors\Pages;

use App\Filament\Exports\DistributorExporter;
use App\Filament\Imports\DistributorImporter;
use App\Filament\Resources\Distributors\DistributorResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListDistributors extends ListRecords
{
    protected static string $resource = DistributorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label(__('messages.actions.import'))
                ->importer(DistributorImporter::class),
            ExportAction::make()
                ->label(__('messages.actions.export'))
                ->exporter(DistributorExporter::class),
        ];
    }

    #[On('refresh-distributors-table')]
    public function refreshTable(): void
    {
        $this->resetTable();
    }
}
