<?php

namespace App\Support;

class Favicon
{
    /**
     * Resolve a public URL for the hospital favicon.
     * Custom favicons uploaded to storage are served from public/favicon-hms.png
     * (storage symlinks are often blocked on cPanel/LiteSpeed).
     */
    public static function url($raw = null): string
    {
        $raw = $raw ?? (\App\Models\SystemSetting::get('favicon', ''));

        if (is_string($raw) && $raw !== '') {
            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://') || str_starts_with($raw, 'data:')) {
                return $raw;
            }

            // Local storage uploads → public copy (web-accessible)
            $publicCopy = public_path('favicon-hms.png');
            if (file_exists($publicCopy)) {
                return asset('favicon-hms.png');
            }

            // Try storage URL anyway
            $path = ltrim($raw, '/');
            if (str_starts_with($path, 'storage/')) {
                return asset($path);
            }

            return asset('storage/' . $path);
        }

        return asset('favicon-hms.png');
    }
}
