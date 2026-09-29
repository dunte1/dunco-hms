<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IcdCodingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'ipd_admission_id', 'opd_visit_id',
        'primary_diagnosis_code', 'primary_diagnosis_desc',
        'secondary_diagnosis_codes', 'procedure_codes',
        'coding_status', 'coded_by', 'coded_at',
        'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'secondary_diagnosis_codes' => 'array',
        'procedure_codes' => 'array',
        'coded_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class, 'ipd_admission_id');
    }

    public function opdVisit(): BelongsTo
    {
        return $this->belongsTo(OpdVisit::class, 'opd_visit_id');
    }

    public function codedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coded_by');
    }

    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
