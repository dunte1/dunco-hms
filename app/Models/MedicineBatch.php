<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id', 'store_id', 'batch_number', 'quantity', 'quantity_sold',
        'unit_cost', 'unit_price', 'manufacturing_date', 'expiry_date', 'status',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity - $this->quantity_sold;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days && !$this->isExpired();
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        return $this->expiry_date ? (int) now()->diffInDays($this->expiry_date, false) : null;
    }
}
