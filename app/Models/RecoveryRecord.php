<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecoveryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ot_schedule_id', 'patient_id', 'admission_time', 'discharge_time',
        'bed_id', 'gcs', 'vital_signs_snapshot', 'pain_score',
        'nausea_vomiting', 'temperature', 'status', 'discharge_criteria_met',
        'discharge_notes', 'nurse_id',
    ];

    protected $casts = [
        'admission_time' => 'datetime',
        'discharge_time' => 'datetime',
        'vital_signs_snapshot' => 'array',
        'nausea_vomiting' => 'boolean',
        'discharge_criteria_met' => 'boolean',
        'temperature' => 'decimal:1',
    ];

    public function otSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}
