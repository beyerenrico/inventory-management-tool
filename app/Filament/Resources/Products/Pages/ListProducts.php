<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Exports\ProductExport;
use App\Filament\Imports\ProductImport;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(ProductImport::class),
            ExportAction::make()
                ->exporter(ProductExport::class),
        ];
    }
}
