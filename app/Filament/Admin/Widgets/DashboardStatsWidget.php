<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::now()->toDateString();
        $warningDate = Carbon::now()->addDays(30)->toDateString();

        // استعلام واحد فقط لجلب كل الإحصائيات بلمح البصر
        $stats = Document::select(
            DB::raw("COUNT(*) as total"),
            DB::raw("COUNT(CASE WHEN expiry_date IS NOT NULL AND expiry_date < '$today' THEN 1 END) as expired"),
            DB::raw("COUNT(CASE WHEN expiry_date IS NOT NULL AND expiry_date BETWEEN '$today' AND '$warningDate' THEN 1 END) as expiringSoon"),
            DB::raw("COUNT(CASE WHEN expiry_date IS NOT NULL AND expiry_date > '$warningDate' THEN 1 END) as active")
        )->first();

        return [
            Stat::make('إجمالي الوثائق', $stats->total)
                ->description('جميع الوثائق المؤرشفة')
                ->icon('heroicon-m-document-text')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('وثائق سارية', $stats->active)
                ->description('سارية المفعول')
                ->icon('heroicon-m-check-badge')
                ->chart([10, 15, 12, 18, 20, 22, 25])
                ->color('success'),

            Stat::make('على وشك الانتهاء', $stats->expiringSoon)
                ->description('تنبيه: تنتهي خلال 30 يوم')
                ->icon('heroicon-m-exclamation-triangle')
                ->chart([5, 4, 6, 3, 5, 2, 1])
                ->color('warning'),

            Stat::make('وثائق منتهية', $stats->expired)
                ->description('تتطلب إجراءً فورياً')
                ->icon('heroicon-m-x-circle')
                ->chart([1, 2, 1, 3, 2, 4, 5])
                ->color('danger'),
        ];
    }
}