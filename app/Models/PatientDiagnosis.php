<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDiagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'diagnosis_category_id', 'diagnosis',
        'symptoms', 'treatment_plan', 'notes', 'diagnosis_date', 'status'
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function diagnosisCategory(): BelongsTo
    {
        return $this->belongsTo(DiagnosisCategory::class);
    }
}
