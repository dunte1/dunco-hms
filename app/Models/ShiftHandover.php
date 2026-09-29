<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftHandover extends Model
{
    use HasFactory;

    protected $fillable = [
        'ward_id', 'shift_type', 'handover_date', 'handover_from_user_id',
        'handover_to_user_id', 'patient_count', 'critical_patients',
        'pending_tasks', 'completed_tasks', 'pending_medications',
        'equipment_issues', 'notes', 'status', 'acknowledged_at',
    ];

    protected $casts = [
        'handover_date' => 'date',
        'patient_count' => 'integer',
        'critical_patients' => 'integer',
        'acknowledged_at' => 'datetime',
    ];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function handoverFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handover_from_user_id');
    }

    public function handoverTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handover_to_user_id');
    }
}
