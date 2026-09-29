<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimRemittance extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'insurance_provider_id',
        'remittance_number',
        'remittance_date',
        'remitted_amount',
        'variance',
        'status',
        'reconciled_by',
        'reconciled_at',
        'notes',
    ];

    protected $casts = [
        'remittance_date' => 'date',
        'remitted_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'reconciled_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ClaimBatch::class, 'batch_id');
    }

    public function insuranceProvider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class);
    }

    public function reconciledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    public function reconcile(User $user): void
    {
        $this->update([
            'status' => 'reconciled',
            'reconciled_by' => $user->id,
            'reconciled_at' => now(),
        ]);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'received' => 'blue',
            'reconciled' => 'green',
            'disputed' => 'red',
            default => 'gray',
        };
    }
}
