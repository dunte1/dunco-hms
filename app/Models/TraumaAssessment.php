<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraumaAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_admission_id', 'patient_id', 'mechanism_of_injury',
        'injury_type', 'head_face_neck', 'chest', 'abdomen',
        'pelvis', 'extremities', 'spinal', 'gcs_total',
        'pupils_left', 'pupils_right', 'vital_signs_snapshot',
        'trauma_score',
    ];

    protected $casts = [
        'vital_signs_snapshot' => 'array',
    ];

    public function emergencyAdmission(): BelongsTo
    {
        return $this->belongsTo(EmergencyAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
