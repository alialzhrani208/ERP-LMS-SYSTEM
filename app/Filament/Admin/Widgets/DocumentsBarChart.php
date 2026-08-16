<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DocumentsBarChart extends ChartWidget
{
    protected ?string $heading = 'مقارنة الوثائق حسب الأقسام';
protected ?string $pollingInterval = null;
    protected function getData(): array
    {
        $today = Carbon::now()->toDateString();
        $upcomingLimit = Carbon::now()->addDays(30)->toDateString();

        // جلب الأقسام مع حساب العد مباشرة من قاعدة البيانات لكل حالة (بدون سحب الوثائق للذاكرة)
        $departments = Department::withCount([
            'documents as active_count' => function ($query) use ($upcomingLimit) {
                $query->where('expiry_date', '>', $upcomingLimit);
            },
            'documents as expiring_soon_count' => function ($query) use ($today, $upcomingLimit) {
                $query->whereBetween('expiry_date', [$today, $upcomingLimit]);
            },
            'documents as expired_count' => function ($query) use ($today) {
                $query->where('expiry_date', '<', $today);
            },
            'documents as no_expiry_count' => function ($query) {
                $query->whereNull('expiry_date');
            },
        ])->get();

        $labels = [];
        $activeCounts = [];
        $expiredCounts = [];
        $noExpiryCounts = [];
        $expiringSoonCounts = [];

        foreach ($departments as $department) {
            $labels[] = $department->name;
            
            // قراءة النتائج الجاهزة والمحسوبة مسبقاً من قاعدة البيانات مباشرة
            $activeCounts[] = $department->active_count;
            $expiringSoonCounts[] = $department->expiring_soon_count;
            $expiredCounts[] = $department->expired_count;
            $noExpiryCounts[] = $department->no_expiry_count;
        }

        return [
            'datasets' => [
                ['label' => 'سارية', 'data' => $activeCounts, 'backgroundColor' => '#1ed762'],
                ['label' => 'على وشك الانتهاء', 'data' => $expiringSoonCounts, 'backgroundColor' => '#f97316'],
                ['label' => 'منتهية', 'data' => $expiredCounts, 'backgroundColor' => '#f02828'],
                ['label' => 'بدون تاريخ انتهاء', 'data' => $noExpiryCounts, 'backgroundColor' => '#64748b'],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}