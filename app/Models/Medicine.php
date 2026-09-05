<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'generic_name', 'category_id', 'brand_id', 'manufacturer', 'dosage_form',
        'strength', 'unit_price', 'stock_quantity', 'minimum_stock',
        'expiry_date', 'description'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(MedicineBrand::class, 'brand_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function storeStocks(): HasMany
    {
        return $this->hasMany(StoreStock::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function getMedicineNameAttribute(): string
    {
        return $this->name . ($this->strength ? " ({$this->strength})" : '');
    }

    public function getTotalStockAcrossStoresAttribute(): int
    {
        return (int) $this->storeStocks()->sum('quantity');
    }

    public function getStockAtStore(int $storeId): int
    {
        return (int) $this->storeStocks()->where('store_id', $storeId)->value('quantity') ?? 0;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
    }

    public function scopeExpiring($query, int $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))->where('expiry_date', '>=', now());
    }
}
