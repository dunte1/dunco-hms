<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationStay extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_admission_id', 'patient_id', 'bed_id',
        'observation_duration_hours', 'observation_purpose',
        'initial_assessment', 'status', 'started_at', 'ended_at',
        'disposition',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function emergencyAdmission(): BelongsTo
    {
        return $this->belongsTo(EmergencyAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }
}
