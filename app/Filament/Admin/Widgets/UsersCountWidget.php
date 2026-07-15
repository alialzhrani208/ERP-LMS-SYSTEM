<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User; // تأكد من استيراد المودل
use App\Models\Departments;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersCountWidget extends StatsOverviewWidget
{
    public static function canView(): bool
{
    return auth()->user()->can('View:UsersCountWidget');
}

    protected function getStats(): array
    {
        return [
            // أضفنا البيانات هنا ليظهر المربع
            Stat::make('عدد المستخدمين', User::count())
                ->description('إجمالي المستخدمين')
                ->descriptionIcon('heroicon-m-user-group'),
            Stat::make('عدد الأقسام', Departments::count())
            ->description('إجمالي الأقسام المسجلة')
            ->descriptionIcon('heroicon-m-building-office-2') // أيقونة مناسبة للأقسام
        ];
    }
}