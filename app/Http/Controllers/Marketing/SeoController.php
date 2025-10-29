<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\SeoRecord;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeoController extends Controller
{
    public function index(Request $request): View
    {
        $query = SeoRecord::with('seoable');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('meta_title', 'like', '%' . $request->search . '%')
                  ->orWhere('focus_keyword', 'like', '%' . $request->search . '%');
            });
        }

        $seoRecords = $query->latest()->paginate(20);
        $blogPosts = BlogPost::all();

        return view('marketing.seo.index', compact('seoRecords', 'blogPosts'));
    }

    public function optimize(Request $request): RedirectResponse
    {
        $request->validate([
            'seoable_type' => 'required|string',
            'seoable_id' => 'required|integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'focus_keyword' => 'nullable|string',
            'meta_keywords' => 'nullable|array',
        ]);

        $modelClass = match($request->seoable_type) {
            'blog_post' => BlogPost::class,
            default => null,
        };

        if (!$modelClass) {
            return redirect()->back()->with('error', 'Invalid content type.');
        }

        $seoable = $modelClass::find($request->seoable_id);
        if (!$seoable) {
            return redirect()->back()->with('error', 'Content not found.');
        }

        SeoRecord::updateOrCreate(
            [
                'seoable_type' => get_class($seoable),
                'seoable_id' => $seoable->id,
            ],
            [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'focus_keyword' => $request->focus_keyword,
                'meta_keywords' => $request->meta_keywords ?? [],
            ]
        );

        return redirect()->back()->with('success', 'SEO optimized successfully.');
    }
}
