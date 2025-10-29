<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number', 'patient_id', 'patient_name', 'patient_phone',
        'doctor_id', 'department', 'queue_type', 'priority', 'status',
        'check_in_time', 'called_time', 'completed_time', 'estimated_wait_time',
        'notes'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'called_time' => 'datetime',
        'completed_time' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
