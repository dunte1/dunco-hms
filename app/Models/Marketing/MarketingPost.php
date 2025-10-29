<?php

namespace App\Models\Marketing;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'type', 'platform', 'hashtags', 'cta_text', 'cta_url',
        'status', 'is_ai_generated', 'ai_model', 'ai_prompt', 'media_urls',
        'campaign_id', 'blog_post_id', 'created_by', 'approved_by',
        'scheduled_at', 'published_at'
    ];

    protected $casts = [
        'media_urls' => 'array',
        'is_ai_generated' => 'boolean',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class);
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scheduledPosts(): HasMany
    {
        return $this->hasMany(ScheduledPost::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(MarketingAnalytic::class);
    }
}
