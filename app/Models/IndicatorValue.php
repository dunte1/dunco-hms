<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorValue extends Model
{
    use HasFactory;

    protected $table = 'indicator_values';

    protected $fillable = [
        'indicator_id', 'period_month', 'period_year', 'numerator',
        'denominator', 'actual_value', 'status', 'recorded_by',
        'recorded_at', 'notes',
    ];

    protected $casts = [
        'numerator' => 'decimal:2',
        'denominator' => 'decimal:2',
        'actual_value' => 'decimal:4',
        'period_month' => 'integer',
        'period_year' => 'integer',
        'recorded_at' => 'datetime',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(QualityIndicator::class, 'indicator_id');
    }

    public function recordedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
