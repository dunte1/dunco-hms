<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WelfareWaiverRequest extends Model
{
    use HasFactory;

    protected $table = 'waiver_requests';

    protected $fillable = [
        'patient_id', 'invoice_id', 'amount', 'reason',
        'supporting_documents', 'status', 'requested_by',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approve(int $approvedBy): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);

        $invoice = $this->invoice;
        if ($invoice) {
            $invoice->discount_amount += $this->amount;
            $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;
            $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
            $invoice->save();
        }

        return true;
    }

    public function reject(int $rejectedBy): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejectedBy,
            'approved_at' => now(),
        ]);

        return true;
    }
}
