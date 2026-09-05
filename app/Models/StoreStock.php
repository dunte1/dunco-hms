<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreStock extends Model
{
    use HasFactory;

    protected $table = 'store_stock';

    protected $fillable = ['store_id', 'medicine_id', 'quantity', 'minimum_stock', 'maximum_stock', 'average_cost'];

    protected $casts = ['average_cost' => 'decimal:2'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->quantity <= 0) return 'out_of_stock';
        if ($this->quantity <= $this->minimum_stock) return 'low_stock';
        if ($this->quantity >= $this->maximum_stock) return 'overstocked';
        return 'normal';
    }
}
