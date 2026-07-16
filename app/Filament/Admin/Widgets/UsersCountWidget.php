<?php

namespace App\Filament\Admin\Widgets;

use App\Models\{User, Departments};
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersCountWidget extends StatsOverviewWidget
{
    // بوابة القفل الكامل للودجت
    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin') 
            || auth()->user()->can('ViewAny:User') 
            || auth()->user()->can('ViewAny:Departments');
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        // خريطة الإحصائيات: نضع كل المربعات وشروطها في مصفوفة واحدة صغيرة
        $cards = [
            'ViewAny:User'        => ['label' => 'عدد المستخدمين', 'count' => User::count(), 'icon' => 'heroicon-m-user-group', 'desc' => 'إجمالي المستخدمين'],
            'ViewAny:Departments' => ['label' => 'عدد الأقسام', 'count' => Departments::count(), 'icon' => 'heroicon-m-building-office-2', 'desc' => 'إجمالي الأقسام المسجلة'],
        ];

        // سطرين فقط يفحصون ويوزعون المربعات مهما كثر عددها تلقائياً!
        foreach ($cards as $permission => $data) {
            if ($user->hasRole('super_admin') || $user->can($permission)) {
                $stats[] = Stat::make($data['label'], $data['count'])
                    ->description($data['desc'])
                    ->descriptionIcon($data['icon']);
            }
        }

        return $stats;
    }
}
