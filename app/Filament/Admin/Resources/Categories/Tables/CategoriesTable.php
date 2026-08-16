<?php

namespace App\Filament\Admin\Resources\Categories\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->sortable()
                ->searchable(), // لو حابب تبحث برقم الـ ID مباشرة

                TextColumn::make('department.name')
                    ->label('القسم')
                    ->sortable()
                    ->searchable()
                    ->placeholder('عام (بدون قسم)'),

                TextColumn::make('name')
                    ->label('اسم التصنيف')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('تاريخ التعديل')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // إضافة فلتر لتصفية التصنيفات حسب القسم
                SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->label('تصفية حسب القسم'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->label('الإجراءات')
                    ->button() // <--- هذه الدالة تحول الشكل إلى زر رسمي
                    ->color(''), // لون الزر (تستطيع تغييرها مثل gray, success, info...)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->stackedOnMobile();

    }
}
