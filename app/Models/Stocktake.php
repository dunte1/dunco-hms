<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stocktake extends Model
{
    use HasFactory;

    protected $fillable = [
        'stocktake_number', 'store_id', 'performed_by', 'approved_by',
        'status', 'stocktake_date', 'notes', 'approval_notes',
        'completed_at', 'approved_at',
    ];

    protected $casts = [
        'stocktake_date' => 'date',
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(StocktakeItem::class);
    }

    public function getVarianceCountAttribute(): int
    {
        return $this->items()->where('variance', '!=', 0)->whereNotNull('variance')->count();
    }

    public function getTotalVarianceValueAttribute(): float
    {
        return (float) $this->items()->where('variance', '!=', 0)->whereNotNull('variance')
            ->sum('variance');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($st) {
            if (!$st->stocktake_number) {
                $st->stocktake_number = 'STK-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
