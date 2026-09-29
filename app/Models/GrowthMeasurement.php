<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthMeasurement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'recorded_date', 'weight_grams', 'height_cm', 'head_circumference_cm',
        'bmi', 'weight_for_age_zscore', 'height_for_age_zscore', 'weight_for_height_zscore',
        'malnutrition_status', 'recorded_by',
    ];

    protected $casts = [
        'recorded_date' => 'date',
        'weight_grams' => 'integer',
        'height_cm' => 'decimal:1',
        'head_circumference_cm' => 'decimal:1',
        'bmi' => 'decimal:2',
        'weight_for_age_zscore' => 'decimal:2',
        'height_for_age_zscore' => 'decimal:2',
        'weight_for_height_zscore' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
