<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Exports\EventExporter;
use App\Filament\Imports\EventImporter;
use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(EventImporter::class),
            Actions\ExportAction::make()
                ->exporter(EventExporter::class)
        ];
    }
}
