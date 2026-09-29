<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhoSafetyChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'ot_schedule_id', 'patient_id', 'checklist_type', 'completed',
        'completed_by', 'completed_at', 'site_marked', 'consent_confirmed',
        'anaesthesia_safety_confirmed', 'instruments_counted', 'equipment_checked',
        'key_concerns_communicated', 'prophylactic_antibiotics_given',
        'essential_imaging_displayed', 'patient_identity_confirmed',
        'surgical_site_confirmed', 'allergies_confirmed', 'blood_loss_risk_assessed',
        'team_introduced', 'recovery_plan_discussed',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'site_marked' => 'boolean',
        'consent_confirmed' => 'boolean',
        'anaesthesia_safety_confirmed' => 'boolean',
        'instruments_counted' => 'boolean',
        'equipment_checked' => 'boolean',
        'key_concerns_communicated' => 'boolean',
        'prophylactic_antibiotics_given' => 'boolean',
        'essential_imaging_displayed' => 'boolean',
        'patient_identity_confirmed' => 'boolean',
        'surgical_site_confirmed' => 'boolean',
        'allergies_confirmed' => 'boolean',
        'blood_loss_risk_assessed' => 'boolean',
        'team_introduced' => 'boolean',
        'recovery_plan_discussed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function otSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
