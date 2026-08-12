<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
           

            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            
            ImportColumn::make('created_at')
                ->label('Created At')
                ->rules(['nullable', 'date']),

            ImportColumn::make('updated_at')
                ->label('Updated At')
                ->rules(['nullable', 'date']),
        ];
    }

    public function resolveRecord(): User
    {
       return User::firstOrNew([
            'email' => $this->data['email'],
        ], [
            'name' => $this->data['name'],
            'password' => Hash::make('password123'), // كلمة مرور افتراضية لتجنب خطأ قاعدة البيانات
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
