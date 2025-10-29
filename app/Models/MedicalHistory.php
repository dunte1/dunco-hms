<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalHistory extends Model
{
    protected $fillable = [
        'patient_id',
        'condition',
        'diagnosis_date',
        'treatment',
        'notes',
        'is_chronic',
        'recorded_date',
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
        'recorded_date' => 'date',
        'is_chronic' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
