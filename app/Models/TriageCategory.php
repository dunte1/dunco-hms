<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TriageCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'color', 'description', 'priority_level', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority_level' => 'integer',
    ];

    public function triages(): HasMany
    {
        return $this->hasMany(Triage::class, 'category_id');
    }
}
