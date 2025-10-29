<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\ScheduledPost;
use App\Models\Marketing\MarketingPost;
use App\Models\Marketing\SocialAccount;
use App\Jobs\Marketing\PublishScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchedulerController extends Controller
{
    public function index(Request $request): View
    {
        $query = ScheduledPost::with(['marketingPost', 'socialAccount']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $scheduledPosts = $query->orderBy('scheduled_at')->paginate(30);
        $posts = MarketingPost::where('status', 'approved')->orWhere('status', 'published')->get();
        $accounts = SocialAccount::where('status', 'active')->get();

        return view('marketing.scheduler.index', compact('scheduledPosts', 'posts', 'accounts'));
    }

    public function schedule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marketing_post_id' => 'required|exists:marketing_posts,id',
            'social_account_id' => 'required|exists:social_accounts,id',
            'scheduled_at' => 'required|date|after:now',
        ]);

        $scheduledPost = ScheduledPost::create($validated);

        // Dispatch job for scheduled time
        PublishScheduledPost::dispatch($scheduledPost)->delay($scheduledPost->scheduled_at);

        return redirect()->route('marketing.scheduler.index')
            ->with('success', 'Post scheduled successfully.');
    }

    public function publishNow(ScheduledPost $scheduledPost): RedirectResponse
    {
        PublishScheduledPost::dispatch($scheduledPost);
        
        return redirect()->back()
            ->with('success', 'Post published immediately.');
    }

    public function cancel(ScheduledPost $scheduledPost): RedirectResponse
    {
        if ($scheduledPost->status === 'pending') {
            $scheduledPost->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Scheduled post cancelled.');
        }

        return redirect()->back()->with('error', 'Cannot cancel this post.');
    }
}
