<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_program_id', 'employee_id', 'status', 'attendance_hours',
        'certificate_issued', 'certificate_path', 'notes'
    ];

    protected $casts = [
        'attendance_hours' => 'integer',
        'certificate_issued' => 'boolean',
    ];

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
