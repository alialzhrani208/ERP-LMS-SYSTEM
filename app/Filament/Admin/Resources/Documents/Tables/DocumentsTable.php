<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان الوثيقة')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('document_number')
                    ->label('رقم الوثيقة')
                    ->searchable(),

                // عرض اسم القسم بدلاً من الرقم
                TextColumn::make('department.name')
                    ->label('القسم')
                    ->sortable()
                    ->searchable(),

                // عرض اسم التصنيف بدلاً من الرقم
                TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->sortable()
                    ->searchable(),

                // زر عرض الملف المرفق الاحترافي
                TextColumn::make('attachment')
                    ->label('الملف')
                    ->badge()
                    ->state(fn ($record) => $record->attachment ? 'عرض الملف' : 'لا يوجد')
                    ->color(fn ($record) => $record->attachment ? 'info' : 'gray')
                    ->url(fn ($record) => $record->attachment ? asset('storage/' . $record->attachment) : null)
                    ->openUrlInNewTab()
                    ->icon(fn ($record) => $record->attachment ? 'heroicon-m-document-text' : null),

                TextColumn::make('document_date')
                    ->label('تاريخ الوثيقة')
                    ->date()
                    ->sortable(),

                TextColumn::make('expiry_date')
                ->label('تاريخ الانتهاء')
               
                ->date()
                 ->placeholder('لا يوجد'),
                // عرض اسم المستخدم الذي أرشف الوثيقة
                TextColumn::make('user.name')
                    ->label('المؤرشف'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('تاريخ التعديل')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            
             ->recordActions([
                \Filament\Actions\ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                ->label('الإجراءات') 
                ->button() // <--- هذه الدالة تحول الشكل إلى زر رسمي
                ->color('') // لون الزر (تستطيع تغييرها مثل gray, success, info...)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}