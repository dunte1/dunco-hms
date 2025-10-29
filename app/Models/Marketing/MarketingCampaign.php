<?php

namespace App\Models\Marketing;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'type', 'start_date', 'end_date',
        'budget', 'spent', 'status', 'target_audience', 'platforms',
        'milestones', 'created_by', 'manager_id', 'notes', 'success_metrics'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'spent' => 'decimal:2',
        'target_audience' => 'array',
        'platforms' => 'array',
        'milestones' => 'array',
        'success_metrics' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(MarketingPost::class);
    }

    public function graphicAssets(): HasMany
    {
        return $this->hasMany(GraphicAsset::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(MarketingAnalytic::class);
    }
}
