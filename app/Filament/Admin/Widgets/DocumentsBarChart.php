<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class DocumentsBarChart extends ChartWidget
{
    protected ?string $heading = 'مقارنة الوثائق حسب الأقسام';
    protected ?string $pollingInterval = null;

    protected function getData(): array
    {
        // جلب الأقسام مع العد المباشر والاعتماد على عمود status المخزن مسبقاً
        $departments = Department::withCount([
            'documents as active_count' => fn ($q) => $q->where('status', 'active'),
            'documents as expiring_soon_count' => fn ($q) => $q->where('status', 'expiring_soon'),
            'documents as expired_count' => fn ($q) => $q->where('status', 'expired'),
            'documents as no_expiry_count' => fn ($q) => $q->where('status', 'no_expiry'),
        ])->get();

        $labels = [];
        $activeCounts = [];
        $expiringSoonCounts = [];
        $expiredCounts = [];
        $noExpiryCounts = [];

        foreach ($departments as $department) {
            $labels[] = $department->name;
            
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