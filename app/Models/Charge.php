<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'invoice_id', 'service_id', 'description',
        'quantity', 'unit_price', 'total', 'tax_rate', 'tax_amount',
        'status', 'charged_by', 'charged_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'charged_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Charge $charge) {
            if (empty($charge->total)) {
                $charge->total = $charge->quantity * $charge->unit_price;
            }
            if (empty($charge->tax_amount) && $charge->tax_rate > 0) {
                $charge->tax_amount = $charge->total * ($charge->tax_rate / 100);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function chargedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'charged_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCharged($query)
    {
        return $query->where('status', 'charged');
    }

    public function reverse(): bool
    {
        if ($this->status !== 'charged') {
            return false;
        }

        $this->update(['status' => 'reversed']);

        return true;
    }
}
