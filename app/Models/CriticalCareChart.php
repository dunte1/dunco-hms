<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriticalCareChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'icu_admission_id', 'patient_id', 'chart_date', 'hour',
        'heart_rate', 'blood_pressure_sys', 'blood_pressure_dia', 'map',
        'respiratory_rate', 'spo2', 'temperature',
        'gcs_eye', 'gcs_verbal', 'gcs_motor', 'gcs_total',
        'pupil_left', 'pupil_right', 'urine_output_ml', 'fluid_balance',
        'notes', 'recorded_by',
    ];

    protected $casts = [
        'chart_date' => 'date',
        'spo2' => 'decimal:1',
        'temperature' => 'decimal:1',
        'urine_output_ml' => 'decimal:1',
        'fluid_balance' => 'decimal:1',
    ];

    public function icuAdmission(): BelongsTo
    {
        return $this->belongsTo(IcuAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
