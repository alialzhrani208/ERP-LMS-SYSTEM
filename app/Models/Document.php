<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Document extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'document_number',
        'department_id',
        'category_id',
        'attachment',
        'document_date',
        'expiry_date',
        'description',
        'user_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // تسجيل الحقول الموجودة في fillable تلقائياً
            ->logOnlyDirty() // تسجيل الحقول التي تغيرت فقط عند التحديث
            ->dontSubmitEmptyLogs(); // عدم حفظ سجلات فارغة إذا لم يحدث تغيير حقيقي
    }

    // العلاقة: الوثيقة تتبع لقسم
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // العلاقة: الوثيقة تتبع لتصنيف
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة: الوثيقة أرشفها مستخدم معين
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
