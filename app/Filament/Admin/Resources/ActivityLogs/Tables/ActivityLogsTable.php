<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Activity::query()->latest())
            ->columns([
                TextColumn::make('causer.name')
                    ->label('المستخدم')
                    ->sortable()
                    ->searchable()
                    ->default('نظام / زائر'),

                TextColumn::make('description')
                    ->label('نوع الحدث')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'إضافة',
                        'updated' => 'تعديل',
                        'deleted' => 'حذف',
                        default => $state,
                    }),

                TextColumn::make('subject_type')
                    ->label('النموذج المستهدف')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'نظام عام')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('وقت الحدث')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    DeleteAction::make(),
                ])
                    ->label('الإجراءات')
                    ->button() // <--- هذه الدالة تحول الشكل إلى زر رسمي
                    ->color(''), // لون الزر (تستطيع تغييرها مثل gray, success, info...)
            ])
            ->filters([
                SelectFilter::make('description')
                    ->label('نوع الحدث')
                    ->options([
                        'created' => 'إضافة',
                        'updated' => 'تعديل',
                        'deleted' => 'حذف',
                    ]),

                // فلتر التاريخ (من - إلى)
                Filter::make('created_at')
                    ->label('فترة الحدث')
                    ->form([
                        DatePicker::make('from')
                            ->label('من تاريخ'),
                        DatePicker::make('to')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['to'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
