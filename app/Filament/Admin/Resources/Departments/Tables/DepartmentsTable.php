<?php

namespace App\Filament\Admin\Resources\Departments\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id')
                ->sortable()
                ->searchable(),
                TextColumn::make('name')
                    ->label('اسم القسم')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('كود القسم')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
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
