<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QualityIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'formula', 'target_value',
        'unit', 'category', 'is_active',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(IndicatorValue::class, 'indicator_id');
    }
}
