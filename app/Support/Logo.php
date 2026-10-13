<?php

namespace App\Support;

class Logo
{
    /**
     * Resolve a public URL for the hospital logo.
     * Storage uploads are served from public/hospital-logo.png
     * (storage symlinks are often blocked on cPanel/LiteSpeed).
     */
    public static function url($raw = null): string
    {
        $raw = $raw ?? (\App\Models\SystemSetting::get('hospital_logo', ''));

        if (is_string($raw) && $raw !== '') {
            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://') || str_starts_with($raw, 'data:')) {
                return $raw;
            }

            $publicCopy = public_path('hospital-logo.png');
            if (file_exists($publicCopy)) {
                return asset('hospital-logo.png');
            }

            $path = ltrim($raw, '/');
            if (str_starts_with($path, 'storage/')) {
                return asset($path);
            }

            return asset('storage/' . $path);
        }

        return asset('favicon-hms.png');
    }
}
