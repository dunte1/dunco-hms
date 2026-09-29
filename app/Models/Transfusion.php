<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transfusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_issue_id', 'patient_id', 'blood_unit_id', 'started_at', 'ended_at',
        'duration_minutes', 'volume_transfused_ml', 'performed_by', 'status', 'stop_reason',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function bloodIssue(): BelongsTo
    {
        return $this->belongsTo(BloodIssue::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bloodUnit(): BelongsTo
    {
        return $this->belongsTo(BloodUnit::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function reaction(): HasOne
    {
        return $this->hasOne(TransfusionReaction::class);
    }
}
