<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispensation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prescription_id', 'patient_id', 'pharmacist_id', 'dispensation_number',
        'total_amount', 'discount_amount', 'tax_amount', 'net_amount',
        'payment_status', 'status', 'dispensed_at', 'verified_at', 'verified_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'dispensed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Dispensation $model) {
            if (empty($model->dispensation_number)) {
                $model->dispensation_number = 'DSP-' . now()->format('Ymd') . '-' . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function pharmacist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DispensationItem::class);
    }

    public function drugReturns(): HasMany
    {
        return $this->hasMany(DrugReturn::class);
    }
}
