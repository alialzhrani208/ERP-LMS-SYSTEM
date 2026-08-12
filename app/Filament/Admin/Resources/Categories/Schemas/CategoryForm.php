<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // تحويل حقل القسم إلى قائمة منسدلة لجلب الأقسام المسجلة
                Select::make('department_id')
                    ->label('القسم التابع له')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('name')
                    ->label('اسم التصنيف')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('وصف التصنيف')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}