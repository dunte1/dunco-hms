<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TriageEscalation extends Model
{
    use HasFactory;

    protected $fillable = [
        'triage_id', 'patient_id', 'escalated_by', 'escalated_to',
        'reason', 'severity', 'status',
        'acknowledged_at', 'resolved_at', 'resolution_notes',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function triage(): BelongsTo
    {
        return $this->belongsTo(Triage::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function escalatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_by');
    }

    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }
}
