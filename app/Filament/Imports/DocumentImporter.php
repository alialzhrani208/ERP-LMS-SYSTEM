<?php

namespace App\Filament\Imports;

use App\Models\Document;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class DocumentImporter extends Importer
{
    protected static ?string $model = Document::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('document_number')
                ->rules(['max:255']),
            ImportColumn::make('department_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('category_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('attachment')
                ->rules(['max:255']),
            ImportColumn::make('document_date')
                ->rules(['date']),
            ImportColumn::make('expiry_date')
                ->rules(['date']),
            ImportColumn::make('description'),
            ImportColumn::make('user_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): Document
    {
        return new Document;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your document import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
