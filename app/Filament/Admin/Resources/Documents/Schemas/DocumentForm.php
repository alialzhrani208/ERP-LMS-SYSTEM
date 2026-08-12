<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان الوثيقة')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('document_number')
                    ->label('رقم الوثيقة / الصادر')
                    ->default(null)
                    ->maxLength(100),

                // قائمة منسدلة للأقسام مع خاصية الـ reactive لتحديث التصنيفات تبعاً لها
                Select::make('department_id')
                    ->label('القسم')
                    ->relationship('department', 'name')
                    ->required()
                    ->reactive()
                    ->searchable()
                    ->preload(),

                // قائمة منسدلة للتصنيفات مرتبطة بالقسم المختار
                Select::make('category_id')
                    ->label('التصنيف')
                    ->relationship(
                        'category', 
                        'name', 
                        fn (Builder $query, callable $get) => 
                            $query->when($get('department_id'), fn ($q, $deptId) => $q->where('department_id', $deptId))
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                // حقل رفع الملفات الاحترافي
                FileUpload::make('attachment')
                    ->label('ملف الوثيقة المرفق')
                    ->disk('public')
                    ->directory('uploads')
                    ->maxSize(10240) // 10 ميجا
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),

                DatePicker::make('document_date')
                    ->label('تاريخ الوثيقة')
                    ->default(now()),

                DatePicker::make('expiry_date')
                    ->label('تاريخ انتهاء الوثيقة (إن وجدت)')
                    ->default(null),

                Textarea::make('description')
                    ->label('ملاحظات أو ملخص')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}