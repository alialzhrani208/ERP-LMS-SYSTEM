<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

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