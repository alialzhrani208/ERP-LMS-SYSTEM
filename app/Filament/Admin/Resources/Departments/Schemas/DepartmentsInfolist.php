<?php

namespace App\Filament\Admin\Resources\Departments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                 ->label('اسم القسم'),
                TextEntry::make('code')
                ->label('كود القسم'),
                TextEntry::make('manager.name') // اسم العلاقة (manager) ثم اسم الحقل (name)
                    ->label('مدير القسم')      // العنوان الذي سيظهر في رأس الجدول
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->label('حالة القسم')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
