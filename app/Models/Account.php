<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'account_category',
        'parent_account_id',
        'description',
        'opening_balance',
        'current_balance',
        'balance_type',
        'is_system_account',
        'is_active',
        'allow_manual_entry',
        'currency',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_system_account' => 'boolean',
        'is_active' => 'boolean',
        'allow_manual_entry' => 'boolean',
    ];

    /**
     * Get the parent account
     */
    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    /**
     * Get child accounts
     */
    public function childAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_account_id');
    }

    /**
     * Get incomes related to this account
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Get expenses related to this account
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'account_id');
    }

    /**
     * Check if account is an asset
     */
    public function isAsset(): bool
    {
        return $this->account_type === 'asset';
    }

    /**
     * Check if account is a liability
     */
    public function isLiability(): bool
    {
        return $this->account_type === 'liability';
    }

    /**
     * Check if account is equity
     */
    public function isEquity(): bool
    {
        return $this->account_type === 'equity';
    }

    /**
     * Check if account is revenue
     */
    public function isRevenue(): bool
    {
        return $this->account_type === 'revenue';
    }

    /**
     * Check if account is an expense
     */
    public function isExpense(): bool
    {
        return $this->account_type === 'expense';
    }

    /**
     * Get the normal balance type for this account
     */
    public function getNormalBalanceAttribute(): string
    {
        return match($this->account_type) {
            'asset', 'expense' => 'debit',
            'liability', 'equity', 'revenue' => 'credit',
        };
    }

    /**
     * Scope to filter active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by account type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    /**
     * Scope to get parent accounts only
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_account_id');
    }

    /**
     * Get full account hierarchy name
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent_account_id) {
            return $this->parentAccount->full_name . ' > ' . $this->account_name;
        }
        return $this->account_name;
    }
}
