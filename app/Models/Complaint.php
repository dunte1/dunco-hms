<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_number', 'patient_id', 'complainant_name', 'complainant_phone',
        'complainant_email', 'department', 'complaint_type', 'description',
        'status', 'priority', 'assigned_to', 'received_by', 'received_at',
        'acknowledged_at', 'resolved_at', 'resolution', 'satisfaction_score',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->complaint_number)) {
                $model->complaint_number = 'CMP' . str_pad(Complaint::count() + 1, 6, '0', STR_PAD_LEFT);
            }
            if (empty($model->received_at)) {
                $model->received_at = now();
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function acknowledge(): void
    {
        $this->update(['status' => 'acknowledged', 'acknowledged_at' => now()]);
    }

    public function resolve(string $resolution, ?int $satisfactionScore = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_at' => now(),
            'satisfaction_score' => $satisfactionScore,
        ]);
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }
}
