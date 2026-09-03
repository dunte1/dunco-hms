<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ICD10Code extends Model
{
    use HasFactory;

    protected $table = 'icd10_codes';

    protected $fillable = [
        'code', 'description', 'category', 'is_chapter_heading',
        'parent_code', 'is_active',
    ];

    protected $casts = [
        'is_chapter_heading' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_code', 'code');
    }
}
