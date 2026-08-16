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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
                    ->label('القسم'),

                // عرض اسم التصنيف بدلاً من الرقم
                TextColumn::make('category.name')
                    ->label('التصنيف'),

                // زر عرض الملف المرفق الاحترافي
                

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
                TextColumn::make('document_status')
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
            \Filament\Tables\Filters\SelectFilter::make('department')
             ->label('القسم')
              ->relationship('department', 'name') // يفترض أن العلاقة اسمها department في موديل الوثيقة
             ->searchable() // يخليك تبحث في أسماء الأقسام لو كانت كثيرة
             ->preload(), // يحمل الأقسام مسبقاً عشان تكون القائمة سريعة
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الوثيقة')
                    ->options([
                        'active' => 'سارية',
                        'expiring_soon' => 'على وشك الانتهاء',
                        'expired' => 'منتهية',
                        'no_expiry' => 'بدون تاريخ انتهاء',
                    ])
                    
                    ->query(function (Builder $query, array $data): Builder {
                        $today = Carbon::now()->toDateString();
                        $warningLimit = Carbon::now()->addDays(30)->toDateString();

                        return $query->when($data['value'], function ($query, $value) use ($today, $warningLimit) {
                            switch ($value) {
                                case 'active':
                                    return $query->where('expiry_date', '>', $warningLimit);
                                case 'expiring_soon':
                                    return $query->whereBetween('expiry_date', [$today, $warningLimit]);
                                case 'expired':
                                    return $query->where('expiry_date', '<', $today);
                                case 'no_expiry':
                                    return $query->whereNull('expiry_date');
                            }
                        });
                    }),
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