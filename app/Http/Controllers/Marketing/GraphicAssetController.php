<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\GraphicAsset;
use App\Models\Marketing\MarketingCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class GraphicAssetController extends Controller
{
    public function index(Request $request): View
    {
        $query = GraphicAsset::with(['campaign', 'creator']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        $assets = $query->latest()->paginate(20);
        $campaigns = MarketingCampaign::all();

        return view('marketing.graphics.index', compact('assets', 'campaigns'));
    }

    public function create(): View
    {
        $campaigns = MarketingCampaign::all();
        return view('marketing.graphics.create', compact('campaigns'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:poster,infographic,social_media,banner',
            'description' => 'nullable|string',
            'image' => 'required|image|max:10240',
            'campaign_id' => 'nullable|exists:marketing_campaigns,id',
            'ai_prompt' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        $path = $request->file('image')->store('graphics', 'public');
        $validated['image_url'] = Storage::url($path);
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'active';

        if ($request->filled('ai_prompt')) {
            $validated['is_ai_generated'] = true;
        }

        GraphicAsset::create($validated);

        return redirect()->route('marketing.graphics.index')
            ->with('success', 'Graphic asset created successfully.');
    }

    public function show(GraphicAsset $graphic): View
    {
        $graphic->load(['campaign', 'creator']);
        return view('marketing.graphics.show', compact('graphic'));
    }

    public function edit(GraphicAsset $graphic): View
    {
        $campaigns = MarketingCampaign::all();
        return view('marketing.graphics.edit', compact('graphic', 'campaigns'));
    }

    public function update(Request $request, GraphicAsset $graphic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:poster,infographic,social_media,banner',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'campaign_id' => 'nullable|exists:marketing_campaigns,id',
            'tags' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($graphic->image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $graphic->image_url));
            }
            
            $path = $request->file('image')->store('graphics', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $graphic->update($validated);

        return redirect()->route('marketing.graphics.index')
            ->with('success', 'Graphic asset updated successfully.');
    }

    public function destroy(GraphicAsset $graphic): RedirectResponse
    {
        if ($graphic->image_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $graphic->image_url));
        }
        
        $graphic->delete();
        return redirect()->route('marketing.graphics.index')
            ->with('success', 'Graphic asset deleted successfully.');
    }
}
