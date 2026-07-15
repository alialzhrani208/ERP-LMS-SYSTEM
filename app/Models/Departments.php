<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- هذا السطر كان ناقصاً
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
protected $fillable = [
        'name',
        'code',
        'manager_id',
        'is_active',
    ];
    //
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
