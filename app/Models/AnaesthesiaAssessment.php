<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnaesthesiaAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'ot_schedule_id', 'assessor_id', 'asa_classification',
        'airway_assessment', 'mallampati_score', 'mouth_opening_cm',
        'neck_mobility', 'previous_anaesthesia_experience', 'airway_teeth_prosthesis',
        'airway_plan', 'anaesthesia_plan', 'risk_assessment', 'assessed_at',
    ];

    protected $casts = [
        'asa_classification' => 'integer',
        'mallampati_score' => 'integer',
        'mouth_opening_cm' => 'decimal:1',
        'airway_teeth_prosthesis' => 'boolean',
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
