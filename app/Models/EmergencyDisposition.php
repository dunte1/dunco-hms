<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyDisposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_admission_id', 'patient_id', 'disposition_type',
        'destination_ward', 'discharge_notes', 'discharge_instructions',
        'discharged_by', 'discharged_at', 'billing_deferred',
    ];

    protected $casts = [
        'discharged_at' => 'datetime',
        'billing_deferred' => 'boolean',
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
        return $this->belongsTo(Doctor::class, 'discharged_by');
    }
}
