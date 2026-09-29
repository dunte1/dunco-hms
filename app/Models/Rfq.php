<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends Model
{
    protected $fillable = [
        'rfq_number', 'title', 'description', 'requisition_id',
        'status', 'issued_date', 'closing_date', 'issued_by',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'closing_date' => 'date',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($rfq) {
            if (!$rfq->rfq_number) {
                $rfq->rfq_number = 'RFQ-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
