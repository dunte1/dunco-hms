<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafetyIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_number', 'incident_type', 'severity', 'description', 'location',
        'body_area_affected', 'injury_type', 'reported_by', 'reported_at',
        'first_aid_given', 'hospital_visit', 'status', 'resolved_by',
        'resolved_at', 'resolution_notes',
    ];

    protected $casts = [
        'first_aid_given' => 'boolean',
        'hospital_visit' => 'boolean',
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
