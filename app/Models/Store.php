<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'address', 'phone', 'manager_id', 'type', 'status', 'is_main'];

    protected $casts = ['is_main' => 'boolean'];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function stockItems(): HasMany
    {
        return $this->hasMany(StoreStock::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function outboundMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'store_id');
    }

    public function inboundMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'to_store_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getLowStockCountAttribute(): int
    {
        return $this->stockItems()->whereColumn('quantity', '<=', 'minimum_stock')->count();
    }

    public function getExpiringCountAttribute(): int
    {
        return $this->batches()->where('status', 'active')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();
    }

    public function getTotalStockValueAttribute(): float
    {
        return (float) $this->stockItems()->sum(DB::raw('quantity * average_cost'));
    }
}
