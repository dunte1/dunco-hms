<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'discount_type', 'value', 'amount',
        'reason', 'approved_by', 'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Discount $discount) {
            if (empty($discount->amount)) {
                $invoice = Invoice::find($discount->invoice_id);
                if ($invoice) {
                    $discount->amount = $discount->discount_type === 'percentage'
                        ? $invoice->subtotal * ($discount->value / 100)
                        : $discount->value;
                }
            }
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
