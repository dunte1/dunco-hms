<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_post_id', 'social_account_id', 'scheduled_at', 'posted_at',
        'status', 'platform_post_id', 'error_message', 'retry_count', 'response_data'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'posted_at' => 'datetime',
        'response_data' => 'array',
    ];

    public function marketingPost(): BelongsTo
    {
        return $this->belongsTo(MarketingPost::class);
    }

    public function socialAccount(): BelongsTo
    {
        return $this->belongsTo(SocialAccount::class);
    }
}
