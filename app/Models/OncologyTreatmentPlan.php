<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OncologyTreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'cancer_registration_id', 'patient_id', 'plan_name', 'treatment_intent',
        'modalities', 'start_date', 'expected_end_date', 'actual_end_date',
        'status', 'created_by',
    ];

    protected $casts = [
        'modalities' => 'array',
        'start_date' => 'date',
        'expected_end_date' => 'date',
        'actual_end_date' => 'date',
    ];

    public function cancerRegistration(): BelongsTo
    {
        return $this->belongsTo(CancerRegistration::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'created_by');
    }

    public function chemoCycles(): HasMany
    {
        return $this->hasMany(ChemoCycle::class, 'treatment_plan_id');
    }

    public function adverseEvents(): HasMany
    {
        return $this->hasMany(AdverseEvent::class);
    }
}
