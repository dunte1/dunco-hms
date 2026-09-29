<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InpatientDischargeSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipd_admission_id', 'patient_id', 'doctor_id', 'admission_diagnosis',
        'discharge_diagnosis', 'procedure_performed', 'treatment_summary',
        'discharge_condition', 'follow_up_instructions', 'medications_on_discharge',
        'signed_by', 'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
