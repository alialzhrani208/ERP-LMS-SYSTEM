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
        'status',
    ];
protected static function boot()
    {
        parent::boot();

        static::saving(function ($document) {
            // يتم التحديث إذا تم تغيير تاريخ الانتهاء أو كانت الوثيقة جديدة
            if ($document->isDirty('expiry_date') || !$document->exists) {
                $today = now()->toDateString();
                $warning = now()->addDays(30)->toDateString();

                if (is_null($document->expiry_date)) {
                    $document->status = 'no_expiry';
                } elseif ($document->expiry_date < $today) {
                    $document->status = 'expired';
                } elseif ($document->expiry_date <= $warning) {
                    $document->status = 'expiring_soon';
                } else {
                    $document->status = 'active';
                }
            }
        });
    }
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
