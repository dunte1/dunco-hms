<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportTemplate extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'config',
        'layout',
        'is_premium',
        'is_active',
        'created_by',
        'last_run_at',
        'usage_count',
    ];

    protected $casts = [
        'config' => 'array',
        'layout' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    /**
     * Get the user who created this template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Increment usage count and update last run time
     */
    public function markAsUsed(): void
    {
        $this->increment('usage_count');
        $this->update(['last_run_at' => now()]);
    }
}
