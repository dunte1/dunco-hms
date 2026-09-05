<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_code',
        'name',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'tax_number',
        'supplier_type',
        'payment_terms',
        'credit_limit',
        'outstanding_balance',
        'status',
        'notes',
        'bank_name',
        'bank_account_number',
        'bank_branch',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
    ];

    /**
     * Get purchase orders for this supplier
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(PurchaseOrderItem::class, PurchaseOrder::class);
    }

    /**
     * Get the supplier's available credit
     */
    public function getAvailableCreditAttribute(): float
    {
        return $this->credit_limit - $this->outstanding_balance;
    }

    /**
     * Check if supplier is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if supplier is blocked
     */
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * Get supplier display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?? $this->name;
    }

    /**
     * Scope to filter active suppliers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by supplier type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('supplier_type', $type);
    }
}
