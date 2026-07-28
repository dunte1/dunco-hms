<?php

namespace App\Jobs\Marketing;

use App\Models\Marketing\ScheduledPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublishScheduledPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ScheduledPost $scheduledPost
    ) {}

    public function handle(): void
    {
        $scheduledPost = $this->scheduledPost;
        $post = $scheduledPost->marketingPost;
        $account = $scheduledPost->socialAccount;

        try {
            // Update status to posting
            $scheduledPost->update(['status' => 'posting']);

            // Post to social media platform
            $response = $this->postToPlatform($post, $account);

            if ($response['success']) {
                $scheduledPost->update([
                    'status' => 'posted',
                    'posted_at' => now(),
                    'platform_post_id' => $response['post_id'] ?? null,
                    'response_data' => $response['data'] ?? null,
                ]);
            } else {
                $scheduledPost->update([
                    'status' => 'failed',
                    'error_message' => $response['error'] ?? 'Unknown error',
                    'retry_count' => $scheduledPost->retry_count + 1,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to publish scheduled post', [
                'scheduled_post_id' => $scheduledPost->id,
                'error' => $e->getMessage(),
            ]);

            $scheduledPost->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'retry_count' => $scheduledPost->retry_count + 1,
            ]);

            throw $e;
        }
    }

    protected function postToPlatform($post, $account): array
    {
        // Route to appropriate platform posting method
        switch ($account->platform) {
            case 'facebook':
                return $this->postToFacebook($post, $account);
            case 'instagram':
                return $this->postToInstagram($post, $account);
            case 'twitter':
                return $this->postToTwitter($post, $account);
            case 'linkedin':
                return $this->postToLinkedIn($post, $account);
            case 'tiktok':
                return $this->postToTikTok($post, $account);
            default:
                return ['success' => false, 'error' => 'Unsupported platform'];
        }
    }

    protected function postToFacebook($post, $account): array
    {
        try {
            $accessToken = $account->access_token ?? config('services.facebook.access_token');
            
            if (!$accessToken) {
                return ['success' => false, 'error' => 'Facebook access token not configured'];
            }

            // For page posts, use page access token
            $pageId = $account->external_id ?? config('services.facebook.page_id');
            
            $response = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/v18.0/{$pageId}/feed", [
                    'message' => $post->content,
                    'link' => $post->link ?? null,
                    'access_token' => $accessToken
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'post_id' => $data['id'] ?? null,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Facebook API error'
            ];
        } catch (\Exception $e) {
            Log::error('Facebook posting failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function postToInstagram($post, $account): array
    {
        try {
            $accessToken = $account->access_token ?? config('services.instagram.access_token');
            $pageId = $account->external_id ?? config('services.instagram.page_id');
            
            if (!$accessToken || !$pageId) {
                return ['success' => false, 'error' => 'Instagram access token or page ID not configured'];
            }

            // Instagram Graph API requires creating a media container first, then publishing
            // Step 1: Create media container
            $mediaResponse = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/v18.0/{$pageId}/media", [
                    'caption' => $post->content,
                    'image_url' => $post->media_url ?? null, // For photo posts
                    'video_url' => $post->video_url ?? null, // For video posts
                    'media_type' => $post->media_type ?? 'IMAGE', // IMAGE, VIDEO, CAROUSEL
                    'access_token' => $accessToken
                ]);

            if (!$mediaResponse->successful()) {
                return [
                    'success' => false,
                    'error' => $mediaResponse->json()['error']['message'] ?? 'Failed to create Instagram media container'
                ];
            }

            $mediaContainerId = $mediaResponse->json()['id'] ?? null;
            
            if (!$mediaContainerId) {
                return ['success' => false, 'error' => 'Failed to get media container ID'];
            }

            // Step 2: Publish the media container
            $publishResponse = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/v18.0/{$pageId}/media_publish", [
                    'creation_id' => $mediaContainerId,
                    'access_token' => $accessToken
                ]);

            if ($publishResponse->successful()) {
                $data = $publishResponse->json();
                return [
                    'success' => true,
                    'post_id' => $data['id'] ?? null,
                    'media_container_id' => $mediaContainerId,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => $publishResponse->json()['error']['message'] ?? 'Failed to publish Instagram post'
            ];
        } catch (\Exception $e) {
            Log::error('Instagram posting failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function postToTwitter($post, $account): array
    {
        try {
            $bearerToken = $account->access_token ?? config('services.twitter.bearer_token');
            $apiKey = config('services.twitter.api_key');
            $apiSecret = config('services.twitter.api_secret');
            
            if (!$bearerToken && (!$apiKey || !$apiSecret)) {
                return ['success' => false, 'error' => 'Twitter credentials not configured'];
            }

            // Twitter API v2 posting
            $response = Http::withToken($bearerToken)
                ->post('https://api.twitter.com/2/tweets', [
                    'text' => substr($post->content, 0, 280) // Twitter character limit
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'post_id' => $data['data']['id'] ?? null,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['detail'] ?? 'Twitter API error'
            ];
        } catch (\Exception $e) {
            Log::error('Twitter posting failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function postToLinkedIn($post, $account): array
    {
        try {
            $accessToken = $account->access_token ?? config('services.linkedin.access_token');
            
            if (!$accessToken) {
                return ['success' => false, 'error' => 'LinkedIn access token not configured'];
            }

            $organizationId = $account->external_id ?? config('services.linkedin.organization_id');
            
            // LinkedIn UGC Post API
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post("https://api.linkedin.com/v2/ugcPosts", [
                    'author' => "urn:li:organization:{$organizationId}",
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $post->content
                            ],
                            'shareMediaCategory' => 'ARTICLE'
                        ]
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'post_id' => $data['id'] ?? null,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'LinkedIn API error'
            ];
        } catch (\Exception $e) {
            Log::error('LinkedIn posting failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function postToTikTok($post, $account): array
    {
        try {
            $accessToken = $account->access_token ?? config('services.tiktok.access_token');
            $appId = config('services.tiktok.app_id');
            $appSecret = config('services.tiktok.app_secret');
            
            if (!$accessToken || !$appId || !$appSecret) {
                return ['success' => false, 'error' => 'TikTok credentials not configured'];
            }

            // TikTok Business API - Initialize upload
            // Step 1: Initialize video upload
            $initResponse = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json'
            ])->post("https://open.tiktokapis.com/v2/post/publish/video/init/", [
                'post_info' => [
                    'title' => substr($post->content, 0, 150), // TikTok title limit
                    'privacy_level' => 'PUBLIC_TO_EVERYONE',
                    'disable_duet' => false,
                    'disable_comment' => false,
                    'disable_stitch' => false,
                    'video_cover_timestamp_ms' => 1000
                ],
                'source_info' => [
                    'source' => 'FILE_UPLOAD'
                ]
            ]);

            if (!$initResponse->successful()) {
                return [
                    'success' => false,
                    'error' => $initResponse->json()['error']['message'] ?? 'Failed to initialize TikTok upload'
                ];
            }

            $uploadData = $initResponse->json()['data'] ?? [];
            $uploadUrl = $uploadData['upload_url'] ?? null;
            $publishId = $uploadData['publish_id'] ?? null;

            if (!$uploadUrl || !$publishId) {
                return ['success' => false, 'error' => 'Failed to get TikTok upload URL'];
            }

            // Step 2: Upload video file to the provided upload URL
            // Note: The actual video file upload would be handled by the media URL provided in $post->media_url
            // TikTok requires a two-step process: init (completed above) and actual file upload to $uploadUrl

            return [
                'success' => true,
                'publish_id' => $publishId,
                'upload_url' => $uploadUrl,
                'message' => 'TikTok upload initialized. Video file upload required to complete publishing.',
                'data' => $uploadData
            ];
        } catch (\Exception $e) {
            Log::error('TikTok posting failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
