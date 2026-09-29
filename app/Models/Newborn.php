<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newborn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'birth_report_id', 'mother_patient_id', 'baby_name',
        'sex', 'date_of_birth', 'time_of_birth', 'birth_weight_grams',
        'gestational_age_weeks', 'apgar_1_min', 'apgar_5_min', 'apgar_10_min',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'birth_weight_grams' => 'integer',
        'gestational_age_weeks' => 'decimal:1',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function birthReport(): BelongsTo
    {
        return $this->belongsTo(BirthReport::class);
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'mother_patient_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(NeonatalAssessment::class);
    }

    public function nicuAdmissions(): HasMany
    {
        return $this->hasMany(NicuAdmission::class);
    }

    public function phototherapySessions(): HasMany
    {
        return $this->hasMany(PhototherapySession::class);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(NeonatalFeed::class);
    }
}
