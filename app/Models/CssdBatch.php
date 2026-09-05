<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CssdBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number', 'instrument_ids', 'sterilization_method', 'started_at',
        'completed_at', 'performed_by', 'temperature', 'pressure',
        'duration_minutes', 'status', 'expiry_date', 'notes',
    ];

    protected $casts = [
        'instrument_ids' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expiry_date' => 'datetime',
    ];
}
