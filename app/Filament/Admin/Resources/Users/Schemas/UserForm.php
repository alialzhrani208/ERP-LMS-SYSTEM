<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
              ->label('الاسم')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الالكتروني')
                    ->email()
                    ->required(),
                
                TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->nullable() // للسماح بأن يكون فارغاً
            ->dehydrated(fn ($state) => filled($state)) 
             ->required(fn (string $operation): bool => $operation === 'create'), // إجباري فقط عند إنشاء مستخدم جديد
                   
             

                Select::make('roles')
                ->label('الصلاحية')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),
                 
                    
            ]);
    }
}