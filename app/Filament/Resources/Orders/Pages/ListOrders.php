<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Exports\OrderExporter;
use App\Filament\Imports\OrderImporter;
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
                ->label(__('messages.actions.import'))
                ->importer(OrderImporter::class),
            ExportAction::make()
                ->label(__('messages.actions.export'))
                ->exporter(OrderExporter::class),
        ];
    }
}
