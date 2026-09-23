<?php

namespace App\Traits;

use App\Models\SystemSetting;

trait SeoTrait
{
    protected function seo(array $data = []): array
    {
        $defaults = [
            'title' => config('app.name', 'Dunco Hospital') . ' - Your Trusted Healthcare Partner',
            'description' => config('app.name', 'Dunco Hospital') . ' - Quality healthcare services with experienced doctors and modern facilities.',
            'keywords' => 'hospital, healthcare, medical, doctor, clinic, Nairobi, Kenya',
            'image' => asset('images/og-default.jpg'),
            'url' => url()->current(),
            'type' => 'website',
            'schema' => null,
        ];

        return array_merge($defaults, $data);
    }

    protected function getCmsSeo(string $page): array
    {
        $metaTitle = SystemSetting::get("cms_{$page}_meta_title", '');
        $metaDescription = SystemSetting::get("cms_{$page}_meta_description", '');
        $metaKeywords = SystemSetting::get("cms_{$page}_meta_keywords", '');

        return [
            'title' => $metaTitle ?: config('app.name', 'Dunco Hospital'),
            'description' => $metaDescription ?: config('app.name', 'Dunco Hospital') . ' - Your trusted healthcare partner',
            'keywords' => $metaKeywords ?: 'hospital, healthcare, medical, doctor',
        ];
    }

    protected function getBlogSeo($post): array
    {
        $metaData = $post->meta_data ?? [];

        return [
            'title' => ($metaData['meta_title'] ?? $post->title) . ' - ' . config('app.name', 'Dunco Hospital'),
            'description' => $metaData['meta_description'] ?? Str::limit(strip_tags($post->content ?? ''), 160),
            'keywords' => $metaData['meta_keywords'] ?? '',
            'type' => 'article',
            'image' => $post->featured_image ?? asset('images/og-default.jpg'),
        ];
    }
}
