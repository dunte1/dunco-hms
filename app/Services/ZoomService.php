<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ZoomService
{
    protected string $accountId;
    protected string $clientId;
    protected string $clientSecret;
    protected string $baseUrl;

    public function __construct()
    {
        $this->accountId = config('services.zoom.account_id', '');
        $this->clientId = config('services.zoom.client_id', '');
        $this->clientSecret = config('services.zoom.client_secret', '');
        $this->baseUrl = config('services.zoom.base_url', 'https://api.zoom.us/v2');
    }

    /**
     * Get access token from Zoom
     */
    protected function getAccessToken(): ?string
    {
        // Check cache first
        $cachedToken = Cache::get('zoom_access_token');
        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'] ?? null;
                
                // Cache token for its lifetime (usually 1 hour)
                $expiresIn = $data['expires_in'] ?? 3600;
                Cache::put('zoom_access_token', $token, now()->addSeconds($expiresIn - 60));
                
                return $token;
            }

            Log::error('Failed to get Zoom access token', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception getting Zoom token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create a Zoom meeting
     */
    public function createMeeting(array $data): array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Zoom'
            ];
        }

        try {
            $userId = $data['user_id'] ?? config('services.zoom.default_user_email', 'me');
            
            $meetingData = [
                'topic' => $data['topic'] ?? 'Telemedicine Consultation',
                'type' => $data['type'] ?? 2, // 1 = instant, 2 = scheduled, 3 = recurring
                'start_time' => $data['start_time'] ?? now()->format('Y-m-d\TH:i:s\Z'),
                'duration' => $data['duration'] ?? 30,
                'timezone' => $data['timezone'] ?? config('app.timezone', 'UTC'),
                'password' => $data['password'] ?? $this->generateMeetingPassword(),
                'settings' => [
                    'host_video' => $data['host_video'] ?? true,
                    'participant_video' => $data['participant_video'] ?? true,
                    'join_before_host' => $data['join_before_host'] ?? false,
                    'mute_upon_entry' => $data['mute_upon_entry'] ?? true,
                    'waiting_room' => $data['waiting_room'] ?? true,
                    'audio' => 'both',
                    'auto_recording' => $data['auto_recording'] ?? 'cloud', // 'cloud', 'local', 'none'
                    'approval_type' => 0, // Automatically approve
                ],
            ];

            $url = $this->baseUrl . '/users/' . $userId . '/meetings';
            
            $response = Http::withToken($token)
                ->post($url, $meetingData);

            if ($response->successful()) {
                $meeting = $response->json();
                
                return [
                    'success' => true,
                    'meeting_id' => $meeting['id'],
                    'join_url' => $meeting['join_url'],
                    'start_url' => $meeting['start_url'],
                    'password' => $meetingData['password'],
                    'data' => $meeting
                ];
            }

            Log::error('Failed to create Zoom meeting', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create Zoom meeting: ' . ($response->json()['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error('Exception creating Zoom meeting', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update a Zoom meeting
     */
    public function updateMeeting(string $meetingId, array $data): array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Zoom'
            ];
        }

        try {
            $url = $this->baseUrl . '/meetings/' . $meetingId;
            
            $response = Http::withToken($token)
                ->patch($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Meeting updated successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to update meeting: ' . ($response->json()['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error('Exception updating Zoom meeting', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a Zoom meeting
     */
    public function deleteMeeting(string $meetingId): array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Zoom'
            ];
        }

        try {
            $url = $this->baseUrl . '/meetings/' . $meetingId;
            
            $response = Http::withToken($token)
                ->delete($url);

            if ($response->successful() || $response->status() === 204) {
                return [
                    'success' => true,
                    'message' => 'Meeting deleted successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to delete meeting: ' . ($response->json()['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error('Exception deleting Zoom meeting', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get meeting details
     */
    public function getMeeting(string $meetingId): array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Zoom'
            ];
        }

        try {
            $url = $this->baseUrl . '/meetings/' . $meetingId;
            
            $response = Http::withToken($token)
                ->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get meeting: ' . ($response->json()['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error('Exception getting Zoom meeting', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get meeting participants
     */
    public function getMeetingParticipants(string $meetingId): array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with Zoom'
            ];
        }

        try {
            $url = $this->baseUrl . '/meetings/' . $meetingId . '/participants';
            
            $response = Http::withToken($token)
                ->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get participants: ' . ($response->json()['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error('Exception getting Zoom participants', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate a secure meeting password
     */
    protected function generateMeetingPassword(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if Zoom is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->accountId) && 
               !empty($this->clientId) && 
               !empty($this->clientSecret);
    }
}

