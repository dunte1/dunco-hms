<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResuscitationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_admission_id', 'patient_id', 'presenting_complaint',
        'initial_assessment', 'airway_status', 'breathing_status',
        'circulation_status', 'disability_neurological', 'exposure',
        'interventions', 'outcome', 'time_of_arrest',
        'resuscitation_duration_minutes', 'performed_by',
    ];

    protected $casts = [
        'time_of_arrest' => 'datetime',
    ];

    public function emergencyAdmission(): BelongsTo
    {
        return $this->belongsTo(EmergencyAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'performed_by');
    }
}
