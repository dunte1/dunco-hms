<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostAnaesthesiaReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'anaesthesia_record_id', 'patient_id', 'review_time', 'consciousness_level',
        'airway_patent', 'breathing_spontaneous', 'heart_rate', 'blood_pressure_sys',
        'blood_pressure_dia', 'spo2', 'temperature', 'pain_score', 'nausea_vomiting',
        'aldrete_score', 'fit_for_discharge', 'reviewer_id', 'notes',
    ];

    protected $casts = [
        'review_time' => 'datetime',
        'consciousness_level' => 'integer',
        'airway_patent' => 'boolean',
        'breathing_spontaneous' => 'boolean',
        'heart_rate' => 'integer',
        'blood_pressure_sys' => 'integer',
        'blood_pressure_dia' => 'integer',
        'spo2' => 'decimal:2',
        'temperature' => 'decimal:1',
        'pain_score' => 'integer',
        'nausea_vomiting' => 'boolean',
        'aldrete_score' => 'integer',
        'fit_for_discharge' => 'boolean',
    ];

    public function anaesthesiaRecord(): BelongsTo
    {
        return $this->belongsTo(AnaesthesiaRecord::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'reviewer_id');
    }
}
