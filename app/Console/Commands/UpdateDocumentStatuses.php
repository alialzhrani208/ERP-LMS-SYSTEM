<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;

class UpdateDocumentStatuses extends Command
{
    protected $signature = 'app:update-document-statuses';

    protected $description = 'تحديث حالات الوثائق تلقائياً';

    public function handle()
    {
        $today = now()->toDateString();
        $warning = now()->addDays(30)->toDateString();

        // 1. منتهية (أقل من اليوم)
        Document::whereNotNull('expiry_date')
            ->where('expiry_date', '<', $today)
            ->update(['status' => 'expired']);

        // 2. على وشك الانتهاء (بين اليوم وخلال 30 يوم)
        Document::whereBetween('expiry_date', [$today, $warning])
            ->update(['status' => 'expiring_soon']);

        // 3. سارية (أكبر من 30 يوم)
        Document::where('expiry_date', '>', $warning)
            ->update(['status' => 'active']);

        // 4. لا يوجد تاريخ انتهاء
        Document::whereNull('expiry_date')
            ->update(['status' => 'no_expiry']);

        $this->info('تم تحديث حالات الوثائق بنجاح وبأداء عالي.');
    }
}