<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'amount', 'payment_method', 'payment_reference',
        'payment_date', 'purpose', 'used_amount', 'balance_amount',
        'status', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
