<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabSpecimen extends Model
{
    use HasFactory;

    protected $fillable = [
        'specimen_number', 'lab_request_id', 'patient_id', 'specimen_type',
        'status', 'collected_by', 'collected_at', 'received_by',
        'received_at', 'rejection_reason',
    ];

    protected $casts = [
        'collected_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function labRequest(): BelongsTo
    {
        return $this->belongsTo(LabRequest::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public static function generateSpecimenNumber(): string
    {
        $prefix = 'SPE-' . date('Y') . '-';
        $lastNumber = self::where('specimen_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('specimen_number');

        if ($lastNumber) {
            $sequence = (int) substr($lastNumber, -6) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    public function receive(User $receiver): void
    {
        $this->update([
            'status' => 'received',
            'received_by' => $receiver->id,
            'received_at' => now(),
        ]);
    }

    public function reject(User $receiver, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'received_by' => $receiver->id,
            'received_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }
}
