<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaimBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'insurance_provider_id',
        'claim_count',
        'total_amount',
        'status',
        'submitted_by',
        'submitted_at',
        'response_received_at',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'response_received_at' => 'datetime',
    ];

    public function insuranceProvider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class, 'batch_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ClaimItem::class);
    }

    public function rejections(): HasMany
    {
        return $this->hasMany(ClaimRejection::class, 'batch_id');
    }

    public function remittances(): HasMany
    {
        return $this->hasMany(ClaimRemittance::class, 'batch_id');
    }

    public function submit(User $user): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_by' => $user->id,
            'submitted_at' => now(),
        ]);

        $this->claims()->update(['status' => 'submitted']);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'accepted' => 'green',
            'rejected' => 'red',
            'partial' => 'yellow',
            default => 'gray',
        };
    }
}
