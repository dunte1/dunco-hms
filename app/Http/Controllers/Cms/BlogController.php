<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::with(['category', 'author'])
            ->where('status', 'published');

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest('published_at')->paginate(6);
        $categories = BlogCategory::where('is_active', true)->get();
        $featuredPosts = BlogPost::where('status', 'published')
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('site.blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    public function show(BlogPost $post): View
    {
        // Increment view count
        $post->increment('views_count');

        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('site.blog.show', compact('post', 'relatedPosts'));
    }

    // Admin CRUD Methods
    public function adminIndex(Request $request): View
    {
        $query = BlogPost::with(['category', 'author']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest()->paginate(15);
        $categories = BlogCategory::all();

        return view('cms.blog.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = BlogCategory::where('is_active', true)->get();
        return view('cms.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');

        if (empty($validated['published_at']) && $validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Store meta fields in meta_data JSON
        $metaData = [];
        if (isset($validated['meta_title'])) {
            $metaData['meta_title'] = $validated['meta_title'];
            unset($validated['meta_title']);
        }
        if (isset($validated['meta_description'])) {
            $metaData['meta_description'] = $validated['meta_description'];
            unset($validated['meta_description']);
        }
        if (isset($validated['meta_keywords'])) {
            $metaData['meta_keywords'] = $validated['meta_keywords'];
            unset($validated['meta_keywords']);
        }
        if (!empty($metaData)) {
            $validated['meta_data'] = $metaData;
        }

        BlogPost::create($validated);

        return redirect()->route('cms.blog.index')
            ->with('success', 'Blog post created successfully!');
    }

    public function edit(BlogPost $post): View
    {
        $categories = BlogCategory::where('is_active', true)->get();
        return view('cms.blog.edit', compact('post', 'categories'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        if (empty($validated['published_at']) && $validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Store meta fields in meta_data JSON
        $metaData = $post->meta_data ?? [];
        if (isset($validated['meta_title'])) {
            $metaData['meta_title'] = $validated['meta_title'];
            unset($validated['meta_title']);
        }
        if (isset($validated['meta_description'])) {
            $metaData['meta_description'] = $validated['meta_description'];
            unset($validated['meta_description']);
        }
        if (isset($validated['meta_keywords'])) {
            $metaData['meta_keywords'] = $validated['meta_keywords'];
            unset($validated['meta_keywords']);
        }
        $validated['meta_data'] = $metaData;

        $post->update($validated);

        return redirect()->route('cms.blog.index')
            ->with('success', 'Blog post updated successfully!');
    }

    public function destroy(BlogPost $post)
    {
        // Delete featured image if exists
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('cms.blog.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}
