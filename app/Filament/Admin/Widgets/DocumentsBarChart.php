<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Department;
use App\Models\Document;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DocumentsBarChart extends ChartWidget
{
    protected ?string $heading = 'مقارنة الوثائق حسب الأقسام (أداء عالي)';

    protected function getData(): array
    {
        $today = Carbon::now();

        // استعلام واحد فقط لجلب الأقسام مع وثائقها
        $departments = Department::with(['documents' => function ($query) use ($today) {
            $query->select('id', 'department_id', 'expiry_date');
        }])->get();

        $labels = [];
        $activeCounts = [];
        $expiredCounts = [];

        foreach ($departments as $department) {
            $labels[] = $department->name;
            
            // الفلترة تتم على المجموعة (Collection) الموجودة في الذاكرة وليس بالاستعلام المتكرر
            $activeCounts[] = $department->documents->where('expiry_date', '>', $today)->count();
            $expiredCounts[] = $department->documents->where('expiry_date', '<', $today)->count();
        }

        return [
            'datasets' => [
                ['label' => 'سارية', 'data' => $activeCounts, 'backgroundColor' => '#22c55e'],
                ['label' => 'منتهية', 'data' => $expiredCounts, 'backgroundColor' => '#ef4444'],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string { return 'bar'; }
}