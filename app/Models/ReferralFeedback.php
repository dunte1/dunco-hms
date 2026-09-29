<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_id', 'treatment_provided', 'outcome',
        'feedback_date', 'feedback_by',
    ];

    protected $casts = [
        'feedback_date' => 'date',
    ];

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function feedbackBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'feedback_by');
    }
}
