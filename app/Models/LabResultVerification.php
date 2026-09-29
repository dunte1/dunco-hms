<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabResultVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_request_item_id', 'verified_by', 'verified_at',
        'approved_by', 'approved_at', 'status', 'notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function labRequestItem(): BelongsTo
    {
        return $this->belongsTo(LabRequestItem::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
