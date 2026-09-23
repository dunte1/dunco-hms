<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Triage extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'opd_visit_id', 'ipd_admission_id', 'triage_number',
        'priority_level', 'temperature', 'pulse_rate', 'systolic_bp', 'diastolic_bp',
        'respiratory_rate', 'oxygen_saturation', 'blood_glucose', 'weight_kg', 'height_cm',
        'chief_complaint', 'triage_notes', 'triaged_by', 'triaged_at',
    ];

    protected $casts = [
        'temperature' => 'decimal:1',
        'oxygen_saturation' => 'decimal:1',
        'blood_glucose' => 'decimal:1',
        'weight_kg' => 'decimal:2',
        'height_cm' => 'decimal:1',
        'triaged_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function opdVisit(): BelongsTo
    {
        return $this->belongsTo(OpdVisit::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function triager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triaged_by');
    }

    public function getBmiAttribute(): ?float
    {
        if ($this->weight_kg && $this->height_cm && $this->height_cm > 0) {
            return round($this->weight_kg / pow($this->height_cm / 100, 2), 1);
        }
        return null;
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority_level) {
            'emergency' => 'red',
            'urgent' => 'orange',
            'semi_urgent' => 'yellow',
            'non_urgent' => 'green',
            default => 'gray',
        };
    }
}
