<?php

namespace App\Support;

class ProfilePhoto
{
    /**
     * Resolve a public URL for a user's profile photo path.
     * Falls back through: absolute URL → /storage route → initials placeholder (empty string).
     */
    public static function url($path = null): string
    {
        if (! is_string($path) || $path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'storage/')) {
            return '/' . $normalized;
        }

        return '/storage/' . $normalized;
    }
}
