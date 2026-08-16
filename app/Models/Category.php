<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'department_id',
        'name',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // تسجيل الحقول الموجودة في fillable تلقائياً
            ->logOnlyDirty() // تسجيل الحقول التي تغيرت فقط عند التحديث
            ->dontSubmitEmptyLogs(); // عدم حفظ سجلات فارغة إذا لم يحدث تغيير حقيقي
    }

    // العلاقة: التصنيف يتبع لقسم معين
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // العلاقة: التصنيف يملك عدة وثائق
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
