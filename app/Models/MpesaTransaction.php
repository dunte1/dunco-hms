<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'patient_id', 'fee_type', 'item_name', 'amount', 'phone',
        'checkout_request_id', 'mpesa_receipt', 'status', 'result_code',
        'result_desc', 'source_type', 'source_id', 'initiated_at',
        'completed_at', 'transaction_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'initiated_at' => 'datetime',
        'completed_at' => 'datetime',
        'transaction_data' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_TIMEOUT = 'timeout';
    public const STATUS_FAILED = 'failed';

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}