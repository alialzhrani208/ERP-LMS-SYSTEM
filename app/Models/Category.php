<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'description',
    ];

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