<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WardDischargePlan extends Model
{
    use HasFactory;

    protected $table = 'discharge_plans';

    protected $fillable = [
        'patient_id', 'ipd_admission_id', 'discharge_date',
        'home_care_needs', 'follow_up_appointments', 'equipment_needs',
        'community_services', 'caregiver_involvement', 'status', 'created_by',
    ];

    protected $casts = [
        'discharge_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
