<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('عنوان الوثيقة')
                    ->columnSpanFull(),

                TextEntry::make('document_number')
                    ->label('رقم الوثيقة / الصادر')
                    ->placeholder('-'),

                // عرض اسم القسم بدلاً من الرقم
                TextEntry::make('department.name')
                    ->label('القسم'),

                // عرض اسم التصنيف بدلاً من الرقم
                TextEntry::make('category.name')
                    ->label('التصنيف')
                    ->placeholder('-'),

                // زر استعراض الملف الاحترافي
                TextEntry::make('attachment')
                    ->label('الملف المرفق')
                    ->badge()
                    ->state(fn ($record) => $record->attachment ? 'عرض الملف' : 'لا يوجد')
                    ->color(fn ($record) => $record->attachment ? 'info' : 'gray')
                    ->url(fn ($record) => $record->attachment ? asset('storage/'.$record->attachment) : null)
                    ->openUrlInNewTab()
                    ->icon(fn ($record) => $record->attachment ? 'heroicon-m-document-text' : null)
                    ->placeholder('لا يوجد'),

                TextEntry::make('document_date')
                    ->label('تاريخ الوثيقة')
                    ->date()
                    ->placeholder('-'),

                TextEntry::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->date()
                    ->placeholder('لا يوجد'),

                // حقل حالة الوثيقة الديناميكي الملون
                TextEntry::make('document_status')
                    ->label('حالة الوثيقة')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if (is_null($record->expiry_date)) {
                            return 'لا يوجد';
                        }

                        $today = Carbon::now()->toDateString();
                        $warningLimit = Carbon::now()->addDays(30)->toDateString();

                        if ($record->expiry_date < $today) {
                            return 'منتهية';
                        }
                        
                        if ($record->expiry_date <= $warningLimit) {
                            return 'على وشك الانتهاء';
                        }

                        return 'سارية';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'سارية' => 'success',
                        'على وشك الانتهاء' => 'warning',
                        'منتهية' => 'danger',
                        'لا يوجد' => 'gray',
                    }),

                // عرض اسم المستخدم الذي أرشف الوثيقة بدلاً من الرقم
                TextEntry::make('user.name')
                    ->label('المؤرشف بواسطة'),

                TextEntry::make('description')
                    ->label('ملاحظات أو ملخص')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('تاريخ التعديل')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}