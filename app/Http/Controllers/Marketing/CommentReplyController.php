<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\CommentReply;
use App\Services\Marketing\AiContentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentReplyController extends Controller
{
    public function __construct(
        protected AiContentService $aiService
    ) {}

    public function index(Request $request): View
    {
        $query = CommentReply::with('reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $replies = $query->latest()->paginate(20);
        $pendingCount = CommentReply::where('status', 'pending')->count();

        return view('marketing.comments.index', compact('replies', 'pendingCount'));
    }

    public function approve(CommentReply $commentReply): RedirectResponse
    {
        if ($commentReply->status === 'pending' && $commentReply->approved_reply) {
            // Post the reply to the platform
            $posted = $this->postReplyToPlatform($commentReply);
            
            if ($posted) {
                $commentReply->update([
                    'status' => 'posted',
                    'reviewed_by' => auth()->id(),
                    'replied_at' => now(),
                ]);

                return redirect()->back()->with('success', 'Reply approved and posted.');
            } else {
                return redirect()->back()->with('error', 'Failed to post reply to platform. Please try again.');
            }
        }

        return redirect()->back()->with('error', 'Reply needs approval text.');
    }

    /**
     * Post reply to the appropriate social media platform
     */
    protected function postReplyToPlatform(CommentReply $commentReply): bool
    {
        try {
            $platform = $commentReply->platform;
            $reply = $commentReply->approved_reply;
            $commentId = $commentReply->external_comment_id;
            $postId = $commentReply->external_post_id;

            switch ($platform) {
                case 'facebook':
                    return $this->postToFacebook($postId, $commentId, $reply);
                    
                case 'twitter':
                case 'x':
                    return $this->postToTwitter($commentId, $reply);
                    
                case 'instagram':
                    return $this->postToInstagram($commentId, $reply);
                    
                case 'linkedin':
                    return $this->postToLinkedIn($commentId, $reply);
                    
                default:
                    \Log::warning('Unknown platform for comment reply', ['platform' => $platform]);
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to post reply to platform', [
                'comment_reply_id' => $commentReply->id,
                'platform' => $commentReply->platform,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Post reply to Facebook
     */
    protected function postToFacebook(string $postId, string $commentId, string $reply): bool
    {
        try {
            $accessToken = config('services.facebook.access_token');
            $pageAccessToken = config('services.facebook.page_access_token');
            
            // Use page access token if available, otherwise use regular access token
            $token = $pageAccessToken ?? $accessToken;
            
            if (!$token) {
                \Log::warning('Facebook access token not configured', [
                    'comment_id' => $commentId
                ]);
                return false;
            }

            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->post("https://graph.facebook.com/v18.0/{$commentId}/comments", [
                    'message' => $reply,
                    'access_token' => $token
                ]);

            if ($response->successful()) {
                $data = $response->json();
                \Log::info('Facebook reply posted successfully', [
                    'comment_id' => $commentId,
                    'reply_id' => $data['id'] ?? null
                ]);
                return true;
            }

            \Log::error('Facebook reply posting failed', [
                'comment_id' => $commentId,
                'error' => $response->json()['error']['message'] ?? 'Unknown error'
            ]);
            return false;
        } catch (\Exception $e) {
            \Log::error('Exception posting Facebook reply', [
                'comment_id' => $commentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Post reply to Twitter/X
     */
    protected function postToTwitter(string $commentId, string $reply): bool
    {
        try {
            $bearerToken = config('services.twitter.bearer_token');
            $accessToken = config('services.twitter.access_token');
            $accessTokenSecret = config('services.twitter.access_token_secret');
            
            if (!$bearerToken && (!$accessToken || !$accessTokenSecret)) {
                \Log::warning('Twitter credentials not configured', [
                    'comment_id' => $commentId
                ]);
                return false;
            }

            // Twitter API v2 - Reply to a tweet
            $response = \Illuminate\Support\Facades\Http::withToken($bearerToken)
                ->post('https://api.twitter.com/2/tweets', [
                    'text' => substr($reply, 0, 280), // Twitter character limit
                    'reply' => [
                        'in_reply_to_tweet_id' => $commentId
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                \Log::info('Twitter reply posted successfully', [
                    'comment_id' => $commentId,
                    'reply_id' => $data['data']['id'] ?? null
                ]);
                return true;
            }

            \Log::error('Twitter reply posting failed', [
                'comment_id' => $commentId,
                'error' => $response->json()['detail'] ?? 'Unknown error'
            ]);
            return false;
        } catch (\Exception $e) {
            \Log::error('Exception posting Twitter reply', [
                'comment_id' => $commentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Post reply to Instagram
     */
    protected function postToInstagram(string $commentId, string $reply): bool
    {
        try {
            $accessToken = config('services.instagram.access_token');
            $pageId = config('services.instagram.page_id');
            
            if (!$accessToken || !$pageId) {
                \Log::warning('Instagram credentials not configured', [
                    'comment_id' => $commentId
                ]);
                return false;
            }

            // Instagram Graph API - Reply to comment
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post("https://graph.facebook.com/v18.0/{$commentId}/replies", [
                    'message' => substr($reply, 0, 1000), // Instagram character limit
                    'access_token' => $accessToken
                ]);

            if ($response->successful()) {
                $data = $response->json();
                \Log::info('Instagram reply posted successfully', [
                    'comment_id' => $commentId,
                    'reply_id' => $data['id'] ?? null
                ]);
                return true;
            }

            \Log::error('Instagram reply posting failed', [
                'comment_id' => $commentId,
                'error' => $response->json()['error']['message'] ?? 'Unknown error'
            ]);
            return false;
        } catch (\Exception $e) {
            \Log::error('Exception posting Instagram reply', [
                'comment_id' => $commentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Post reply to LinkedIn
     */
    protected function postToLinkedIn(string $commentId, string $reply): bool
    {
        try {
            $accessToken = config('services.linkedin.access_token');
            $organizationId = config('services.linkedin.organization_id');
            
            if (!$accessToken || !$organizationId) {
                \Log::warning('LinkedIn credentials not configured', [
                    'comment_id' => $commentId
                ]);
                return false;
            }

            // LinkedIn API - Reply to comment
            // LinkedIn uses URN format for comments: urn:li:comment:(activity:xxx,xxx)
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Restli-Protocol-Version' => '2.0.0'
                ])
                ->post("https://api.linkedin.com/v2/socialActions/{$commentId}/comments", [
                    'actor' => "urn:li:organization:{$organizationId}",
                    'message' => [
                        'text' => substr($reply, 0, 1000) // LinkedIn character limit
                    ]
                ]);

            if ($response->successful() || $response->status() === 201) {
                $data = $response->json();
                \Log::info('LinkedIn reply posted successfully', [
                    'comment_id' => $commentId,
                    'reply_id' => $data['id'] ?? null
                ]);
                return true;
            }

            \Log::error('LinkedIn reply posting failed', [
                'comment_id' => $commentId,
                'error' => $response->json()['message'] ?? 'Unknown error'
            ]);
            return false;
        } catch (\Exception $e) {
            \Log::error('Exception posting LinkedIn reply', [
                'comment_id' => $commentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function reject(CommentReply $commentReply): RedirectResponse
    {
        $commentReply->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Reply rejected.');
    }

    public function generateReply(Request $request, CommentReply $commentReply): RedirectResponse
    {
        $sentiment = $this->aiService->analyzeSentiment($commentReply->original_comment);
        $reply = $this->aiService->generateReply($commentReply->original_comment, $sentiment);

        $commentReply->update([
            'sentiment' => $sentiment,
            'ai_generated_reply' => $reply,
            'approved_reply' => $reply, // Auto-approve for now
        ]);

        return redirect()->back()->with('success', 'Reply generated successfully.');
    }
}
