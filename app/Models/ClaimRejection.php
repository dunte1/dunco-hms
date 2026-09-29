<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimRejection extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_claim_id',
        'batch_id',
        'rejection_code',
        'rejection_reason',
        'amount_rejected',
        'action_taken',
        'action_by',
        'action_at',
    ];

    protected $casts = [
        'amount_rejected' => 'decimal:2',
        'action_at' => 'datetime',
    ];

    public function insuranceClaim(): BelongsTo
    {
        return $this->belongsTo(InsuranceClaim::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ClaimBatch::class, 'batch_id');
    }

    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
