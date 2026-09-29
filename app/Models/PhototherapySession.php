<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhototherapySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'newborn_id', 'nicu_admission_id', 'start_time', 'end_time',
        'duration_hours', 'light_type', 'bilirubin_before', 'bilirubin_after',
        'eye_protection', 'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_hours' => 'decimal:1',
        'bilirubin_before' => 'decimal:1',
        'bilirubin_after' => 'decimal:1',
        'eye_protection' => 'boolean',
    ];

    public function newborn(): BelongsTo
    {
        return $this->belongsTo(Newborn::class);
    }

    public function nicuAdmission(): BelongsTo
    {
        return $this->belongsTo(NicuAdmission::class);
    }
}
