<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NurseAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nurse_user_id', 'ward_id', 'shift_id', 'allocated_date', 'shift_type',
        'bed_range_start', 'bed_range_end', 'patient_count', 'status',
        'allocated_by', 'notes',
    ];

    protected $casts = [
        'allocated_date' => 'date',
        'bed_range_start' => 'integer',
        'bed_range_end' => 'integer',
        'patient_count' => 'integer',
    ];

    public function nurseUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_user_id');
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function allocatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
}
