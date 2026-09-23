<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = url('/');
        $lastModified = now()->toAtomString();

        $urls = [];

        // Static pages
        $staticPages = [
            '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
            '/services' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            '/doctors' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            '/about' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/contact' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/features' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            '/book-appointment' => ['priority' => '0.9', 'changefreq' => 'monthly'],
            '/login' => ['priority' => '0.3', 'changefreq' => 'monthly'],
            '/register' => ['priority' => '0.3', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $path => $params) {
            $urls[] = [
                'loc' => $baseUrl . $path,
                'lastmod' => $lastModified,
                'changefreq' => $params['changefreq'],
                'priority' => $params['priority'],
            ];
        }

        // Blog posts
        try {
            $posts = BlogPost::where('is_published', true)
                ->latest('published_at')
                ->limit(200)
                ->get(['id', 'slug', 'updated_at']);

            foreach ($posts as $post) {
                $urls[] = [
                    'loc' => $baseUrl . '/blog/' . ($post->slug ?? $post->id),
                    'lastmod' => $post->updated_at->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            }
        } catch (\Exception $e) {
            // Blog table may not exist
        }

        // Doctors
        try {
            $doctors = Doctor::latest('updated_at')->limit(100)->get(['id', 'updated_at']);
            foreach ($doctors as $doctor) {
                $urls[] = [
                    'loc' => $baseUrl . '/doctors?doctor=' . $doctor->id,
                    'lastmod' => $doctor->updated_at->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
            }
        } catch (\Exception $e) {
            // Doctors table may not exist
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url['loc']}</loc>\n";
            $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
