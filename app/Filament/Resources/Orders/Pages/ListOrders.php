<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Exports\OrderExport;
use App\Filament\Imports\OrderImport;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(OrderImport::class),
            ExportAction::make()
                ->exporter(OrderExport::class),
        ];
    }
}
