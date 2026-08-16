<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // تسجيل الحقول الموجودة في fillable تلقائياً
            ->logOnlyDirty() // تسجيل الحقول التي تغيرت فقط عند التحديث
            ->dontSubmitEmptyLogs(); // عدم حفظ سجلات فارغة إذا لم يحدث تغيير حقيقي
    }

    // العلاقة: القسم يملك عدة تصنيفات
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // العلاقة: القسم يملك عدة وثائق
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
