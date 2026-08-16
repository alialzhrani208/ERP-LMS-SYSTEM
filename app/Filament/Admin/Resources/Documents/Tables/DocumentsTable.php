<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use App\Filament\Exports\DocumentExporter;
use App\Filament\Imports\DocumentImporter;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

                // عرض اسم القسم
                TextColumn::make('department.name')
                    ->label('القسم'),

                // عرض اسم التصنيف
                TextColumn::make('category.name')
                    ->label('التصنيف'),

                TextColumn::make('document_date')
                    ->label('تاريخ الوثيقة')
                    ->date()
                    ->sortable(),

                TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->date()
                    ->placeholder('لا يوجد')
                    ->sortable(),

                // عمود حالة الوثيقة الديناميكي الملون
                TextColumn::make('status')
                    ->label('حالة الوثيقة')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expiring_soon' => 'warning',
                        'expired' => 'danger',
                        'no_expiry' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'سارية',
                        'expiring_soon' => 'على وشك الانتهاء',
                        'expired' => 'منتهية',
                        'no_expiry' => 'لا يوجد',
                    }),

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

                TextColumn::make('attachment')
                    ->label('الملف')
                    ->badge()
                    ->state(fn ($record) => $record->attachment ? 'عرض الملف' : 'لا يوجد')
                    ->color(fn ($record) => $record->attachment ? 'info' : 'gray')
                    ->url(fn ($record) => $record->attachment ? asset('storage/' . $record->attachment) : null)
                    ->openUrlInNewTab()
                    ->icon(fn ($record) => $record->attachment ? 'heroicon-m-document-text' : null),
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
                ]),
            ])
            ->filters([
                // فلتر حسب القسم
                SelectFilter::make('department')
                    ->label('القسم')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),

                // فلتر حسب حالة الوثيقة (معتمد على الحقل المباشر في قاعدة البيانات بكل سرعة وكفاءة)
                SelectFilter::make('status')
                    ->label('حالة الوثيقة')
                    ->options([
                        'active' => 'سارية',
                        'expiring_soon' => 'على وشك الانتهاء',
                        'expired' => 'منتهية',
                        'no_expiry' => 'بدون تاريخ انتهاء',
                    ]),

                // فلتر تاريخ الوثيقة (من - إلى)
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

                // فلتر تاريخ انتهاء الوثيقة (من - إلى)
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
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->label('الإجراءات')
                    ->button()
                    ->color('gray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('تصدير سجل محدد Excel')
                        ->icon('heroicon-o-arrow-up-tray')
                        ->exporter(DocumentExporter::class),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->stackedOnMobile();
    }
}