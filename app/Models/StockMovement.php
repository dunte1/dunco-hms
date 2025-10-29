<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'movement_number',
        'medicine_id',
        'purchase_order_id',
        'user_id',
        'movement_type',
        'direction',
        'quantity',
        'stock_before',
        'stock_after',
        'unit_cost',
        'total_cost',
        'batch_number',
        'expiry_date',
        'movement_date',
        'reference_type',
        'reference_id',
        'from_location',
        'to_location',
        'notes',
        'reason',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'movement_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the medicine this movement belongs to
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * Get the purchase order if this is from a PO
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the user who made this movement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reference model (polymorphic)
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if this is a stock in movement
     */
    public function isStockIn(): bool
    {
        return $this->direction === 'in';
    }

    /**
     * Check if this is a stock out movement
     */
    public function isStockOut(): bool
    {
        return $this->direction === 'out';
    }

    /**
     * Scope to filter stock in movements
     */
    public function scopeStockIn($query)
    {
        return $query->where('direction', 'in');
    }

    /**
     * Scope to filter stock out movements
     */
    public function scopeStockOut($query)
    {
        return $query->where('direction', 'out');
    }

    /**
     * Scope to filter by movement type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }
}
