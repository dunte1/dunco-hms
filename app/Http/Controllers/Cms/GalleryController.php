<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = GalleryItem::with('category')
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $items = $query->orderBy('sort_order')->paginate(12);
        $categories = GalleryCategory::where('is_active', true)->get();
        $featuredItems = GalleryItem::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('site.gallery.index', compact('items', 'categories', 'featuredItems'));
    }

    // Admin CRUD Methods
    public function adminIndex(Request $request): View
    {
        $query = GalleryItem::with('category');

        if ($request->filled('category')) {
            $query->where('gallery_category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->orderBy('sort_order')->latest()->paginate(15);
        $categories = GalleryCategory::all();

        return view('cms.gallery.index', compact('items', 'categories'));
    }

    public function create(): View
    {
        $categories = GalleryCategory::where('is_active', true)->get();
        return view('cms.gallery.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'type' => 'required|in:image,video',
            'file_path' => 'required_if:type,image|image|max:5120',
            'video_url' => 'required_if:type,video|url',
            'thumbnail' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('gallery', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('gallery/thumbnails', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        GalleryItem::create($validated);

        return redirect()->route('cms.gallery.index')
            ->with('success', 'Gallery item added successfully!');
    }

    public function show(GalleryItem $item): View
    {
        return view('cms.gallery.show', compact('item'));
    }

    public function edit(GalleryItem $item): View
    {
        $categories = GalleryCategory::where('is_active', true)->get();
        return view('cms.gallery.edit', compact('item', 'categories'));
    }

    public function update(Request $request, GalleryItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'type' => 'required|in:image,video',
            'file_path' => 'nullable|image|max:5120',
            'video_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($item->file_path) {
                \Storage::disk('public')->delete($item->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('gallery', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($item->thumbnail) {
                \Storage::disk('public')->delete($item->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('gallery/thumbnails', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $item->update($validated);

        return redirect()->route('cms.gallery.index')
            ->with('success', 'Gallery item updated successfully!');
    }

    public function destroy(GalleryItem $item)
    {
        // Delete files if they exist
        if ($item->file_path) {
            \Storage::disk('public')->delete($item->file_path);
        }
        if ($item->thumbnail) {
            \Storage::disk('public')->delete($item->thumbnail);
        }

        $item->delete();

        return redirect()->route('cms.gallery.index')
            ->with('success', 'Gallery item deleted successfully!');
    }
}
