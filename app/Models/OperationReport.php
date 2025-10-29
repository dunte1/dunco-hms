<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number', 'patient_id', 'operation_name', 'operation_description',
        'operation_date', 'start_time', 'end_time', 'duration_minutes',
        'surgeon_id', 'assistant_doctor_id', 'anesthesiologist_id', 'nurse_id',
        'anesthesia_type', 'pre_operation_notes', 'operation_notes',
        'post_operation_notes', 'complications', 'outcome', 'follow_up_instructions'
    ];

    protected $casts = [
        'operation_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function surgeon(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'surgeon_id');
    }

    public function assistantDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'assistant_doctor_id');
    }

    public function anesthesiologist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'anesthesiologist_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class);
    }
}
