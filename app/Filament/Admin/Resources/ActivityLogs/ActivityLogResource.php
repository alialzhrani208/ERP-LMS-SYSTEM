<?php

namespace App\Filament\Admin\Resources\ActivityLogs;

use App\Filament\Admin\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Admin\Resources\ActivityLogs\Pages\ViewActivityLog;
use App\Filament\Admin\Resources\ActivityLogs\Schemas\ActivityLogInfolist;
use App\Filament\Admin\Resources\ActivityLogs\Tables\ActivityLogsTable; // استدعاء ملف التفاصيل
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'حركات النظام';

    protected static ?string $pluralModelLabel = 'حركات النظام';

    protected static string|UnitEnum|null $navigationGroup = 'إدارة النظام';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    // تعطيل إنشاء سجلات جديدة يدوياً
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        // هذا الكود يتأكد من أن المستخدم يمتلك صلاحية الدخول فعلياً
        return auth()->user()->can('ViewAny:Activity');
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),

        ];
    }
}
