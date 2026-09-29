<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureFlag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'is_enabled', 'config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'config' => 'array',
    ];

    public static function isEnabled(string $slug): bool
    {
        $flag = static::where('slug', $slug)->first();
        return $flag?->is_enabled ?? false;
    }
}
