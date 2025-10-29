<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelemedicineSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'patient_id', 'doctor_id', 'scheduled_time', 'start_time',
        'end_time', 'session_type', 'platform', 'meeting_url', 'meeting_id',
        'status', 'notes', 'session_data'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'session_data' => 'array',
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
