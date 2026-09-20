<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Module extends Model
{
    protected $fillable = [
        'name', 'slug', 'category', 'is_enabled', 'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    const CACHE_KEY = 'enabled_modules';

    /**
     * The list of modules that are always enabled and cannot be disabled
     * (core infrastructure required for the system to function).
     */
    public static function alwaysEnabledSlugs(): array
    {
        return ['dashboard', 'settings', 'roles-alc', 'users'];
    }

    public static function isEnabled(?string $slug = null): bool
    {
        if (!$slug) {
            return true;
        }

        $slug = self::normalizeSlug($slug);

        if (in_array($slug, self::alwaysEnabledSlugs(), true)) {
            return true;
        }

        $enabled = self::enabledSlugs();

        return in_array($slug, $enabled, true);
    }

    protected static function normalizeSlug(?string $slug): string
    {
        return is_string($slug) ? str($slug)->slug('-')->toString() : '';
    }

    public static function enabledSlugs(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::query()->where('is_enabled', true)->pluck('slug')->map(fn ($s) => self::normalizeSlug($s))->values()->all();
        });
    }

    public static function resetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    protected static function booted(): void
    {
        static::saved(function () {
            self::resetCache();
        });
        static::deleted(function () {
            self::resetCache();
        });
    }
}
