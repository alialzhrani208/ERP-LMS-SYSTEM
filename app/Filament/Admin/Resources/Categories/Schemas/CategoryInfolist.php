<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('department.name')
                    ->label('اسم القسم')
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label('اسم التصنيف'),
                TextEntry::make('description')
                    ->label('وصف')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->placeholder('-'),

            ]);
    }
}
