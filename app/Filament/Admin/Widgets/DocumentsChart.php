<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DocumentsChart extends ChartWidget
{
    protected ?string $heading = 'تحليل حالة الوثائق';

    protected function getData(): array
    {
        $today = Carbon::now();

        // استعلام واحد لجلب جميع الحالات دفعة واحدة
        $stats = Document::select(
            DB::raw("COUNT(CASE WHEN expiry_date > '$today' THEN 1 END) as active"),
            DB::raw("COUNT(CASE WHEN expiry_date < '$today' THEN 1 END) as expired"),
            DB::raw("COUNT(CASE WHEN expiry_date IS NULL THEN 1 END) as noExpiry")
        )->first();

        return [
            'datasets' => [
                [
                    'label' => 'الوثائق',
                    'data' => [$stats->active, $stats->expired, $stats->noExpiry],
                    'backgroundColor' => ['#22c55e', '#ef4444', '#64748b'],
                ],
            ],
            'labels' => ['سارية', 'منتهية', 'بدون تاريخ انتهاء'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}