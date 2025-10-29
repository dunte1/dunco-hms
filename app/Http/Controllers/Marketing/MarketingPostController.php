<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\MarketingPost;
use App\Models\Marketing\MarketingCampaign;
use App\Models\BlogPost;
use App\Services\Marketing\AiContentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarketingPostController extends Controller
{
    public function __construct(
        protected AiContentService $aiService
    ) {}

    public function index(Request $request): View
    {
        $query = MarketingPost::with(['creator', 'campaign', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest()->paginate(20);
        $campaigns = MarketingCampaign::all();

        return view('marketing.posts.index', compact('posts', 'campaigns'));
    }

    public function create(): View
    {
        $campaigns = MarketingCampaign::all();
        $blogPosts = BlogPost::where('status', 'published')->get();

        return view('marketing.posts.create', compact('campaigns', 'blogPosts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:social,blog,email,sms',
            'platform' => 'nullable|in:facebook,instagram,twitter,linkedin,tiktok',
            'hashtags' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'cta_url' => 'nullable|url',
            'media_urls' => 'nullable|array',
            'campaign_id' => 'nullable|exists:marketing_campaigns,id',
            'blog_post_id' => 'nullable|exists:blog_posts,id',
            'scheduled_at' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->input('status', 'draft');

        MarketingPost::create($validated);

        return redirect()->route('marketing.posts.index')
            ->with('success', 'Marketing post created successfully.');
    }

    public function show(MarketingPost $post): View
    {
        $post->load(['creator', 'campaign', 'approver', 'scheduledPosts.socialAccount', 'analytics']);
        
        return view('marketing.posts.show', compact('post'));
    }

    public function edit(MarketingPost $post): View
    {
        $campaigns = MarketingCampaign::all();
        $blogPosts = BlogPost::where('status', 'published')->get();

        return view('marketing.posts.edit', compact('post', 'campaigns', 'blogPosts'));
    }

    public function update(Request $request, MarketingPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:social,blog,email,sms',
            'platform' => 'nullable|in:facebook,instagram,twitter,linkedin,tiktok',
            'hashtags' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'cta_url' => 'nullable|url',
            'media_urls' => 'nullable|array',
            'campaign_id' => 'nullable|exists:marketing_campaigns,id',
            'blog_post_id' => 'nullable|exists:blog_posts,id',
            'scheduled_at' => 'nullable|date',
            'status' => 'required|in:draft,pending,approved,published,archived',
        ]);

        $post->update($validated);

        return redirect()->route('marketing.posts.index')
            ->with('success', 'Marketing post updated successfully.');
    }

    public function destroy(MarketingPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('marketing.posts.index')
            ->with('success', 'Marketing post deleted successfully.');
    }

    public function approve(Request $request, MarketingPost $post): RedirectResponse
    {
        $post->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Post approved successfully.');
    }
}
