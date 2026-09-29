<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataSubjectRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'request_type', 'status', 'requested_at',
        'deadline_at', 'completed_at', 'notes', 'processed_by',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'deadline_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function process(int $processorId): bool
    {
        return $this->update([
            'status' => 'processing',
            'processed_by' => $processorId,
        ]);
    }

    public function complete(int $processorId): bool
    {
        return $this->update([
            'status' => 'completed',
            'processed_by' => $processorId,
            'completed_at' => now(),
        ]);
    }

    public function reject(int $processorId, ?string $notes = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'processed_by' => $processorId,
            'notes' => $notes,
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->deadline_at && $this->deadline_at->isPast() && $this->status !== 'completed';
    }
}
