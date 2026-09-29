<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IcuAdmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'ipd_admission_id', 'ward_id', 'bed_id', 'unit_type',
        'admission_from', 'admission_datetime', 'admission_diagnosis',
        'admitting_doctor_id', 'status', 'discharge_datetime',
        'discharge_destination', 'discharge_condition',
    ];

    protected $casts = [
        'admission_datetime' => 'datetime',
        'discharge_datetime' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function admittingDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'admitting_doctor_id');
    }

    public function charts(): HasMany
    {
        return $this->hasMany(CriticalCareChart::class);
    }

    public function ventilatorSettings(): HasMany
    {
        return $this->hasMany(VentilatorSetting::class);
    }

    public function abgResults(): HasMany
    {
        return $this->hasMany(AbgResult::class);
    }

    public function sedationScores(): HasMany
    {
        return $this->hasMany(SedationScore::class);
    }

    public function infusionRecords(): HasMany
    {
        return $this->hasMany(InfusionRecord::class);
    }
}
