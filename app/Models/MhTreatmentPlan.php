<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MhTreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'mh_assessment_id', 'diagnosis', 'goals',
        'interventions', 'medications', 'follow_up_frequency',
        'status', 'created_by',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function mhAssessment(): BelongsTo
    {
        return $this->belongsTo(MhAssessment::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
