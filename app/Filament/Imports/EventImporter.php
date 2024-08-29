<?php

namespace App\Filament\Imports;

use App\Models\Event;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EventImporter extends Importer
{
    protected static ?string $model = Event::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('start_date')
                ->rules(['datetime']),
            ImportColumn::make('end_date')
                ->rules(['datetime']),
        ];
    }

    public function resolveRecord(): ?Event
    {
        // return Event::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Event();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your event import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
