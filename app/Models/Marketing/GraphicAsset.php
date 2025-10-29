<?php

namespace App\Models\Marketing;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GraphicAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'description', 'image_url', 'image_metadata',
        'is_ai_generated', 'ai_model', 'ai_prompt', 'brand_color_primary',
        'brand_color_secondary', 'hospital_logo_url', 'campaign_id',
        'created_by', 'tags', 'status'
    ];

    protected $casts = [
        'image_metadata' => 'array',
        'is_ai_generated' => 'boolean',
        'tags' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
