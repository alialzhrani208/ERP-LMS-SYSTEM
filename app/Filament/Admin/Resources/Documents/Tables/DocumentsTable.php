<?php

namespace App\Filament\Admin\Resources\Documents\Tables;
use Filament\Actions\ActionGroup;
use App\Filament\Exports\DocumentExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\DocumentImporter;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
          ->searchPlaceholder('ابحث بالاسم او الرقم فقط')
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
                    ->label('التصنيف'),
                    

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
            ->headerActions([
            ActionGroup::make([
            
             ExportAction::make()
              ->label('تصدير كافة السجلات Excel')
              ->icon('heroicon-o-arrow-up-tray')
             ->exporter(DocumentExporter::class),
            ImportAction::make()
             ->label('استيراد من ملف Excel')
            ->importer(DocumentImporter::class)
            ->icon('heroicon-o-arrow-down-tray'),
            ])
            ])
            ->filters([
                //
                Filter::make('document_date')
    ->form([
        DatePicker::make('from')->label('تاريخ الوثيقة من'),
        DatePicker::make('until')->label('تاريخ الوثيقة إلى'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when($data['from'], fn ($q, $date) => $q->whereDate('document_date', '>=', $date))
            ->when($data['until'], fn ($q, $date) => $q->whereDate('document_date', '<=', $date));
    }),

// 2. فلتر حسب تاريخ انتهاء الوثيقة (expiry_date)
Filter::make('expiry_date')
    ->form([
        DatePicker::make('from')->label('انتهاء الوثيقة من'),
        DatePicker::make('until')->label('انتهاء الوثيقة إلى'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when($data['from'], fn ($q, $date) => $q->whereDate('expiry_date', '>=', $date))
            ->when($data['until'], fn ($q, $date) => $q->whereDate('expiry_date', '<=', $date));
    }),


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
                 ExportBulkAction::make()
                        ->label('تصدير سجل محدد Excel')
                        ->icon('heroicon-o-arrow-up-tray')
                        ->exporter(DocumentExporter::class),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}