<?php

namespace App\Filament\Resources\Distributors\Pages;

use App\Filament\Exports\DistributorExport;
use App\Filament\Imports\DistributorImport;
use App\Filament\Resources\Distributors\DistributorResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListDistributors extends ListRecords
{
    protected static string $resource = DistributorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(DistributorImport::class),
            ExportAction::make()
                ->exporter(DistributorExport::class),
        ];
    }
}
