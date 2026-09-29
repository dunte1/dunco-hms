<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'referral_status_history';

    protected $fillable = [
        'referral_id', 'from_status', 'to_status',
        'changed_by', 'changed_at', 'notes',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
