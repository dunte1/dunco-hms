<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdverseEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'chemo_cycle_id', 'treatment_plan_id',
        'grade', 'event_type', 'description', 'onset_date',
        'resolved_date', 'management', 'reported_by',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function chemoCycle(): BelongsTo
    {
        return $this->belongsTo(ChemoCycle::class);
    }

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(OncologyTreatmentPlan::class, 'treatment_plan_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
