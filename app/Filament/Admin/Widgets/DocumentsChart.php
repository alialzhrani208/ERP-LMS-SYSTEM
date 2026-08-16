<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DocumentsChart extends ChartWidget
{
    protected ?string $heading = 'توزيع الوثائق حسب التصنيفات';
protected ?string $pollingInterval = null;
    protected function getData(): array
    {
        // جلب أعلى 10 تصنيفات مع عدد وثائقها
        $topCategories = Document::query()
            ->join('categories', 'documents.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('COUNT(documents.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topCategoryNames = $topCategories->pluck('category_name');
        
        $otherTotal = Document::query()
            ->join('categories', 'documents.category_id', '=', 'categories.id')
            ->whereNotIn('categories.name', $topCategoryNames)
            ->count();

        $labels = $topCategories->pluck('category_name')->toArray();
        $data = $topCategories->pluck('total')->toArray();

        if ($otherTotal > 0) {
            array_push($labels, 'أخرى');
            array_push($data, $otherTotal);
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد الوثائق',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6', '#1ed762', '#f97316', '#f02828', '#8b5cf6', 
                        '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16', '#64748b'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}