<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

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