<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SedationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'icu_admission_id', 'patient_id', 'score_type', 'score_value',
        'assessment_time', 'notes', 'assessed_by',
    ];

    protected $casts = [
        'assessment_time' => 'datetime',
    ];

    public function icuAdmission(): BelongsTo
    {
        return $this->belongsTo(IcuAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function assessedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
