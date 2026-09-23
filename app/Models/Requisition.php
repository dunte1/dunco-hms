<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_number', 'requesting_store_id', 'supplying_store_id',
        'requested_by', 'approved_by', 'status', 'reason', 'rejection_reason',
        'notes', 'approved_at', 'fulfilled_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    public function requestingStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'requesting_store_id');
    }

    public function supplyingStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'supplying_store_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RequisitionItem::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($req) {
            if (!$req->requisition_number) {
                $req->requisition_number = 'REQ-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
