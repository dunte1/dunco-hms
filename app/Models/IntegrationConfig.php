<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegrationConfig extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'type', 'provider', 'credentials', 'config',
        'is_active', 'is_sandbox', 'last_synced_at', 'last_error',
    ];

    protected $casts = [
        'credentials' => 'array',
        'config' => 'array',
        'is_active' => 'boolean',
        'is_sandbox' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
