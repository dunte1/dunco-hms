<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreopAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'ot_schedule_id', 'assessor_id', 'asa_classification',
        'airway_assessment', 'comorbidities', 'allergies', 'medications',
        'npo_status', 'fasting_hours', 'airway_teeth_prosthesis', 'weight_kg',
        'allergies_confirmed', 'risks_identified', 'plan', 'assessed_at',
    ];

    protected $casts = [
        'npo_status' => 'boolean',
        'airway_teeth_prosthesis' => 'boolean',
        'allergies_confirmed' => 'boolean',
        'weight_kg' => 'decimal:2',
        'assessed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function otSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'assessor_id');
    }
}
