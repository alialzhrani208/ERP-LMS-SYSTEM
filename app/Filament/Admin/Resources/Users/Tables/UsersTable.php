<?php

namespace App\Filament\Admin\Resources\Users\Tables;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Exports\UserExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\UserImporter;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->searchPlaceholder('ابحث عن المستخدم بالاسم فقط')
            ->columns([
            
                TextColumn::make('name')
                ->label('الاسم')
                    ->searchable(),
                    
                TextColumn::make('email')
                    ->label('البريد الالكتروني'),
                
                    TextColumn::make('roles.name')
                    ->placeholder('لا توجد صلاحية')
                    ->label('الصلاحية'),
                    
                         
                   TextColumn::make('created_at')
                ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->sortable(),
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
             ->exporter(UserExporter::class),
            
             ImportAction::make()
        ->label('استيراد من ملف Excel')
        ->importer(UserImporter::class)
        ->icon('heroicon-o-arrow-down-tray'),
            ])
            ])
            ->filters([
                //
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('من تاريخ'),
                        DatePicker::make('created_until')->label('إلى تاريخ'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) $indicators[] = 'من: ' . $data['created_from'];
                        if ($data['created_until'] ?? null) $indicators[] = 'إلى: ' . $data['created_until'];
                        return $indicators;
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
                    // تم إضافة زر تصدير السجلات المحددة هنا في المكان الصحيح
                    ExportBulkAction::make()
                        ->label('تصدير سجل محدد Excel')
                        ->icon('heroicon-o-arrow-up-tray')
                        ->exporter(UserExporter::class),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->stackedOnMobile();
    }
}