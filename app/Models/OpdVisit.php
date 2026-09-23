<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OpdVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'visit_date', 'visit_type', 'status',
        'triage_id', 'triage_notes', 'chief_complaint', 'diagnosis',
        'prescription', 'consultation_fee',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'consultation_fee' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function triage(): HasOne
    {
        return $this->hasOne(Triage::class);
    }

    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class);
    }

    public function labRequests(): HasMany
    {
        return $this->hasMany(LabRequest::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    public function nursingCarePlans(): HasMany
    {
        return $this->hasMany(NursingCarePlan::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Registered',
            'triaged' => 'Triaged',
            'in_consultation' => 'In Consultation',
            'lab_pending' => 'Lab Pending',
            'pharmacy_pending' => 'Pharmacy Pending',
            'billing_pending' => 'Billing Pending',
            'completed' => 'Completed',
            'discharged' => 'Discharged',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'registered' => 'blue',
            'triaged' => 'yellow',
            'in_consultation' => 'purple',
            'lab_pending' => 'orange',
            'pharmacy_pending' => 'cyan',
            'billing_pending' => 'pink',
            'completed' => 'green',
            'discharged' => 'gray',
            default => 'gray',
        };
    }
}
