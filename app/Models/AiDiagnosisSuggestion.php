<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiDiagnosisSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'symptoms', 'vital_signs', 'lab_results',
        'suggested_diagnoses', 'confidence_score', 'reasoning', 'status'
    ];

    protected $casts = [
        'symptoms' => 'array',
        'vital_signs' => 'array',
        'lab_results' => 'array',
        'suggested_diagnoses' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
