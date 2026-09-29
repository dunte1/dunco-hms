<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChemoCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id', 'patient_id', 'cycle_number', 'scheduled_date',
        'actual_date', 'status', 'delayed_reason', 'prescribed_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'actual_date' => 'date',
    ];

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(OncologyTreatmentPlan::class, 'treatment_plan_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescribedBy(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'prescribed_by');
    }

    public function infusions(): HasMany
    {
        return $this->hasMany(ChemoInfusion::class);
    }
}
