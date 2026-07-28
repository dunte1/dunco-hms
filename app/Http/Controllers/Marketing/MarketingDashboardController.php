<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\MarketingPost;
use App\Models\Marketing\MarketingCampaign;
use App\Models\Marketing\MarketingAnalytic;
use App\Models\Marketing\ScheduledPost;
use App\Models\Marketing\CommentReply;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class MarketingDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_posts' => MarketingPost::count(),
            'published_posts' => MarketingPost::where('status', 'published')->count(),
            'draft_posts' => MarketingPost::where('status', 'draft')->count(),
            'pending_posts' => MarketingPost::where('status', 'pending')->count(),
            'active_campaigns' => MarketingCampaign::where('status', 'active')->count(),
            'scheduled_posts' => ScheduledPost::where('status', 'pending')->count(),
            'pending_replies' => CommentReply::where('status', 'pending')->count(),
            'total_engagement' => MarketingAnalytic::sum('engagement'),
        ];

        // Recent activity
        $recentPosts = MarketingPost::with(['creator', 'campaign'])
            ->latest()
            ->limit(10)
            ->get();

        $upcomingSchedules = ScheduledPost::with(['marketingPost', 'socialAccount'])
            ->where('status', 'pending')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        // Analytics trends (last 30 days)
        $analytics = MarketingAnalytic::select(
            DB::raw('DATE(analytics_date) as date'),
            DB::raw('SUM(engagement) as total_engagement'),
            DB::raw('SUM(impressions) as total_impressions'),
            DB::raw('SUM(reach) as total_reach')
        )
            ->where('analytics_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing posts
        $topPosts = MarketingPost::with(['analytics'])
            ->join('marketing_analytics', 'marketing_posts.id', '=', 'marketing_analytics.marketing_post_id')
            ->select(
                'marketing_posts.id',
                'marketing_posts.title',
                'marketing_posts.content',
                'marketing_posts.status',
                'marketing_posts.created_at',
                'marketing_posts.updated_at',
                'marketing_posts.created_by',
                'marketing_posts.campaign_id',
                DB::raw('SUM(marketing_analytics.engagement) as total_engagement')
            )
            ->groupBy(
                'marketing_posts.id',
                'marketing_posts.title',
                'marketing_posts.content',
                'marketing_posts.status',
                'marketing_posts.created_at',
                'marketing_posts.updated_at',
                'marketing_posts.created_by',
                'marketing_posts.campaign_id'
            )
            ->orderBy('total_engagement', 'desc')
            ->limit(5)
            ->get();

        return view('marketing.dashboard.index', compact('stats', 'recentPosts', 'upcomingSchedules', 'analytics', 'topPosts'));
    }
}
