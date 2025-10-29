<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_post_id', 'campaign_id', 'platform', 'platform_post_id',
        'analytics_date', 'impressions', 'reach', 'engagement', 'likes',
        'comments', 'shares', 'clicks', 'saves', 'demographics', 'metrics_raw'
    ];

    protected $casts = [
        'analytics_date' => 'date',
        'demographics' => 'array',
        'metrics_raw' => 'array',
    ];

    public function marketingPost(): BelongsTo
    {
        return $this->belongsTo(MarketingPost::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class);
    }
}
