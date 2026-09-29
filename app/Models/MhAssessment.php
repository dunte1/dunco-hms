<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MhAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'assessment_date', 'presenting_complaint',
        'mental_status_examination', 'risk_assessment',
        'suicidal_ideation', 'homicidal_ideation', 'self_harm_risk',
        'substance_use', 'functioning_score', 'assessed_by',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'suicidal_ideation' => 'boolean',
        'homicidal_ideation' => 'boolean',
        'self_harm_risk' => 'boolean',
        'functioning_score' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function assessedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function treatmentPlans()
    {
        return $this->hasMany(MhTreatmentPlan::class, 'mh_assessment_id');
    }
}
