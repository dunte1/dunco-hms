<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'country',
        'exchange_rate',
        'is_base_currency',
        'is_active',
        'decimal_places',
        'position',
        'description',
        'last_updated',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_base_currency' => 'boolean',
        'is_active' => 'boolean',
        'decimal_places' => 'integer',
        'last_updated' => 'datetime',
    ];

    /**
     * Get accounts using this currency
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'currency', 'code');
    }

    /**
     * Get the base currency
     */
    public static function getBaseCurrency(): ?self
    {
        return static::where('is_base_currency', true)->first();
    }

    /**
     * Get all active currencies
     */
    public static function getActiveCurrencies()
    {
        return static::where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Convert amount from this currency to another currency
     */
    public function convertTo(float $amount, string $targetCurrencyCode): float
    {
        if ($this->code === $targetCurrencyCode) {
            return $amount;
        }

        $targetCurrency = static::where('code', $targetCurrencyCode)->first();
        if (!$targetCurrency) {
            return $amount;
        }

        // Convert to base currency first, then to target currency
        $baseAmount = $amount / $this->exchange_rate;
        return $baseAmount * $targetCurrency->exchange_rate;
    }

    /**
     * Format amount with currency symbol
     */
    public function formatAmount(float $amount): string
    {
        $formattedAmount = number_format($amount, $this->decimal_places);
        
        if ($this->position === 'before') {
            return $this->symbol . $formattedAmount;
        } else {
            return $formattedAmount . ' ' . $this->symbol;
        }
    }

    /**
     * Set as base currency (only one can be base)
     */
    public function setAsBaseCurrency(): void
    {
        // Remove base currency from all other currencies
        static::where('is_base_currency', true)->update(['is_base_currency' => false]);
        
        // Set this currency as base
        $this->update([
            'is_base_currency' => true,
            'exchange_rate' => 1.000000,
        ]);
    }

    /**
     * Update exchange rate
     */
    public function updateExchangeRate(float $newRate): void
    {
        $this->update([
            'exchange_rate' => $newRate,
            'last_updated' => now(),
        ]);
    }

    /**
     * Scope for active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for base currency
     */
    public function scopeBase($query)
    {
        return $query->where('is_base_currency', true);
    }
}