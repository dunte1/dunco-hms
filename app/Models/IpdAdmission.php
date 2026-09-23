<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IpdAdmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'bed_id', 'ward_id', 'payment_id',
        'admission_date', 'discharge_date', 'status', 'diagnosis',
        'treatment_plan', 'ama_reason', 'ama_signed',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'ama_signed' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class);
    }

    public function nursingCarePlans(): HasMany
    {
        return $this->hasMany(NursingCarePlan::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    public function getDurationOfStayAttribute(): ?int
    {
        if ($this->admission_date && $this->discharge_date) {
            return $this->admission_date->diffInDays($this->discharge_date);
        }
        if ($this->admission_date) {
            return $this->admission_date->diffInDays(now());
        }
        return null;
    }
}
