<?php

namespace App\Models\Marketing;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform', 'account_name', 'account_id', 'access_token',
        'refresh_token', 'token_secret', 'token_expires_at', 'status',
        'is_default', 'metadata', 'user_id'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_default' => 'boolean',
        'metadata' => 'array',
    ];

    protected $hidden = [
        'access_token', 'refresh_token', 'token_secret'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledPosts(): HasMany
    {
        return $this->hasMany(ScheduledPost::class);
    }

    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }
}
