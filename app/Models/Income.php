<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'income_number',
        'account_id',
        'income_category',
        'source',
        'patient_id',
        'invoice_id',
        'payment_id',
        'amount',
        'income_date',
        'payment_method',
        'reference_number',
        'description',
        'notes',
        'recorded_by',
        'is_recurring',
        'recurring_frequency',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'income_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get the account this income belongs to
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the patient associated with this income
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the invoice associated with this income
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the payment associated with this income
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the user who recorded this income
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('income_date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('income_category', $category);
    }

    /**
     * Scope to filter by payment method
     */
    public function scopePaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope to get today's income
     */
    public function scopeToday($query)
    {
        return $query->whereDate('income_date', today());
    }

    /**
     * Scope to get this month's income
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('income_date', now()->month)
                     ->whereYear('income_date', now()->year);
    }

    /**
     * Scope to get recurring incomes
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }
}
