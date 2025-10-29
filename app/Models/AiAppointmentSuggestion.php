<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAppointmentSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'suggested_time', 'confidence_score',
        'reasoning', 'doctor_availability', 'patient_preferences', 'status'
    ];

    protected $casts = [
        'suggested_time' => 'datetime',
        'doctor_availability' => 'array',
        'patient_preferences' => 'array',
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
