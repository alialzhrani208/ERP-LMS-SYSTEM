<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->label('الاسم'),
                TextEntry::make('email')
                    ->label('البريد الالكتروني'),
                TextEntry::make('created_at')
                ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                ->label('تاريخ التعديل')
                    ->dateTime()
                    ->placeholder('-'),
                    TextEntry::make('password')
                ->label('كلمة المرور'),
               
                    TextEntry::make('roles.name')
                ->label('الصلاحية') // عنوان الحقل
            ]);
    }
}
