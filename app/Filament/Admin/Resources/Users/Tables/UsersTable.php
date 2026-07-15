<?php

namespace App\Filament\Admin\Resources\Users\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Exports\UserExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use App\Models\User;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            
                TextColumn::make('name')
                ->label('الاسم')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد الالكتروني')
                    ->searchable(),
                TextColumn::make('created_at')
                ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                ->label('تاريخ التعديل')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('roles.name')
                    ->label('الصلاحية')
                    
            ])
            ->headerActions([
             ExportAction::make()
             
              ->label('تصدير كافة السجلات')
             ->exporter(UserExporter::class),
             
            ])
            ->filters([
                //
                
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ExportBulkAction::make()
                 ->label('تصدير سجل مستخدم محدد ')
                ->exporter(UserExporter::class),

                ]),
                
            ]);
    }
}
