<?php

namespace App\Filament\Admin\Resources\Departments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->label('اسم القسم')
                ,
                TextEntry::make('code')
                ->label('كود القسم')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->placeholder('-'),
                
            ]);
    }
}
