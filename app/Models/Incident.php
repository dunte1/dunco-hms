<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_number', 'incident_type', 'severity', 'description', 'location',
        'reported_by', 'reported_at', 'department', 'patient_id', 'status',
        'root_cause', 'corrective_action', 'resolved_by', 'resolved_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->incident_number)) {
                $model->incident_number = 'INC' . str_pad(Incident::count() + 1, 6, '0', STR_PAD_LEFT);
            }
            if (empty($model->reported_at)) {
                $model->reported_at = now();
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function reportedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function investigate(): void
    {
        $this->update(['status' => 'investigating']);
    }

    public function resolve(int $resolvedBy, string $rootCause, string $correctiveAction): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_by' => $resolvedBy,
            'resolved_at' => now(),
            'root_cause' => $rootCause,
            'corrective_action' => $correctiveAction,
        ]);
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }
}
