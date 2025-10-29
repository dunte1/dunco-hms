<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyAdmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_number', 'patient_id', 'patient_name', 'patient_phone',
        'ambulance_id', 'admission_time', 'triage_level', 'chief_complaint',
        'vital_signs', 'initial_assessment', 'status', 'discharge_notes',
        'discharge_time'
    ];

    protected $casts = [
        'admission_time' => 'datetime',
        'discharge_time' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ambulance(): BelongsTo
    {
        return $this->belongsTo(Ambulance::class);
    }
}
