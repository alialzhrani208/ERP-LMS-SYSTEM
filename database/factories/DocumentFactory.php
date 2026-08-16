<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
    $arabicTitles = [
            'عقد عمل موظف',
            'اتفاقية سرية معلومات',
            'خطاب ترقية إدارية',
            'شهادة تأمين صحي',
            'سجل تجاري للشركات',
            'رخصة بلدية سارية',
            'تقرير الأداء السنوي',
            'فاتورة مشتريات مالية',
            'مذكرة تفاهم مشتركة',
            'قرار إداري داخلي',
        ];

        // مصفوفة أوصاف عربية واقعية
            $arabicDescriptions = [
            'تم توثيق هذا المستند وإرفاقه بنجاح في النظام.',
            'نسخة أصلية معتمدة وموقعة من قبل الإدارة المختصة.',
            'يحتاج هذا المستند إلى مراجعة دورية قبل تاريخ الانتهاء.',
            'تمت أرشفة هذا الملف بناءً على طلب القسم المعني.',
            'مستند رسمي تابع للأرشيف العام للشركة.',
        ];
        // مصفوفة لاختيار تواريخ متنوعة (ماضي، قريب، مستقبل، فارغ)
        $expiryDates = [
            now()->subDays(rand(1, 60))->toDateString(), // منتهي (في الماضي)
            now()->addDays(rand(1, 15))->toDateString(), // على وشك الانتهاء (خلال 15 يوم)
            now()->addDays(rand(40, 200))->toDateString(), // ساري (في المستقبل البعيد)
            null, // بدون تاريخ انتهاء
        ];

        return [
'title' => $this->faker->randomElement($arabicTitles) . ' - ' . rand(100, 999),
        'document_number' => 'DOC-' . strtoupper(uniqid()),            
            // اختيار الأقسام الموجودة لديك فقط (1 أو 3)
            'department_id' => $this->faker->randomElement([1, 3]),
            
            // اختيار التصنيفات الموجودة لديك (من 1 إلى 4)
            'category_id' => $this->faker->randomElement([1, 2, 3, 4]),
            
            'attachment' => 'uploads/01M04A02XJP2BZEJWEQ8FM25ZQ.pdf',
            'document_date' => now()->subDays(rand(5, 100))->toDateString(),
            'expiry_date' => $this->faker->randomElement($expiryDates),
'description' => $this->faker->randomElement($arabicDescriptions),            
            // اختيار المستخدمين الموجودين لديك فقط (من 4 إلى 7)
            'user_id' => $this->faker->randomElement([4, 5, 6, 7]),
        ];
    }
}