<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NursingCarePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'ipd_admission_id', 'opd_visit_id', 'care_plan_number',
        'nursing_diagnosis', 'goal', 'interventions', 'expected_outcome',
        'actual_outcome', 'evaluation', 'status', 'assigned_nurse_id', 'created_by',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function opdVisit(): BelongsTo
    {
        return $this->belongsTo(OpdVisit::class);
    }

    public function assignedNurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'assigned_nurse_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($plan) {
            if (!$plan->care_plan_number) {
                $plan->care_plan_number = 'NCP-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
