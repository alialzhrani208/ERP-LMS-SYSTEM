<?php

namespace App\Filament\Admin\Resources\Departments\Schemas;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\User; // استيراد موديل المستخدم
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
class DepartmentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                 ->label('اسم القسم')
                    ->required(),
                TextInput::make('code')
                 ->label('كود القسم')
                    ->required(),

                // التعديل هنا:
                Select::make('manager_id')
                    ->label('مدير القسم') // اختيار اسم يظهر للمستخدم
                    ->relationship('manager', 'name') // يفترض وجود علاقة باسم manager في موديل Department
                    ->options(User::all()->pluck('name', 'id')) // جلب الأسماء والأرقام التعريفية
                    ->searchable() // يتيح لك البحث في القائمة إذا كان عدد المستخدمين كبيراً
                    ->preload() // تحميل الخيارات مسبقاً
                    ->default(null),

                Toggle::make('is_active')
                ->label('حالة القسم')
                    ->required(),
            ]);
    }
}