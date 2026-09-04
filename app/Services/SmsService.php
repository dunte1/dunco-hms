<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;

class SmsService
{
    protected ?TwilioClient $twilio = null;
    protected string $provider;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'twilio');
        
        if ($this->provider === 'twilio' && $this->isTwilioConfigured()) {
            $this->twilio = new TwilioClient(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );
        }
    }

    /**
     * Send SMS to a phone number
     */
    public function send(string $to, string $message, array $options = []): array
    {
        return match($this->provider) {
            'twilio' => $this->sendViaTwilio($to, $message, $options),
            'africas_talking' => $this->sendViaAfricasTalking($to, $message, $options),
            'nexmo' => $this->sendViaNexmo($to, $message, $options),
            default => [
                'success' => false,
                'message' => 'SMS provider not configured'
            ]
        };
    }

    /**
     * Send SMS via Twilio
     */
    protected function sendViaTwilio(string $to, string $message, array $options): array
    {
        if (!$this->twilio) {
            return [
                'success' => false,
                'message' => 'Twilio not configured'
            ];
        }

        try {
            $from = $options['from'] ?? config('services.twilio.from');
            
            $result = $this->twilio->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $message
                ]
            );

            return [
                'success' => true,
                'message_id' => $result->sid,
                'status' => $result->status,
                'message' => 'SMS sent successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Twilio SMS failed', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS via Africa's Talking
     */
    protected function sendViaAfricasTalking(string $to, string $message, array $options): array
    {
        try {
            $apiKey = config('services.africas_talking.api_key');
            $username = config('services.africas_talking.username');
            $from = $options['from'] ?? config('services.africas_talking.shortcode');

            $response = Http::withHeaders([
                'apiKey' => $apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post('https://api.africastalking.com/version1/messaging', [
                'username' => $username,
                'to' => $to,
                'message' => $message,
                'from' => $from
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message_id' => $data['SMSMessageData']['Recipients'][0]['messageId'] ?? null,
                    'message' => 'SMS sent successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to send SMS via Africa\'s Talking'
            ];
        } catch (\Exception $e) {
            Log::error('Africa\'s Talking SMS failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS via Nexmo/Vonage
     */
    protected function sendViaNexmo(string $to, string $message, array $options): array
    {
        try {
            $apiKey = config('services.nexmo.api_key');
            $apiSecret = config('services.nexmo.api_secret');
            $from = $options['from'] ?? config('services.nexmo.from');

            $response = Http::post('https://rest.nexmo.com/sms/json', [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'to' => $to,
                'from' => $from,
                'text' => $message
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['messages'][0]['status']) && $data['messages'][0]['status'] == '0') {
                    return [
                        'success' => true,
                        'message_id' => $data['messages'][0]['message-id'] ?? null,
                        'message' => 'SMS sent successfully'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to send SMS via Nexmo'
            ];
        } catch (\Exception $e) {
            Log::error('Nexmo SMS failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS
     */
    public function sendBulk(array $recipients, string $message, array $options = []): array
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            $result = $this->send($recipient, $message, $options);
            $results[] = [
                'recipient' => $recipient,
                'result' => $result
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return [
            'success' => $failureCount === 0,
            'total' => count($recipients),
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'results' => $results
        ];
    }

    /**
     * Check if any SMS provider is configured
     */
    public function isConfigured(): bool
    {
        return match($this->provider) {
            'twilio' => $this->isTwilioConfigured(),
            'africas_talking' => !empty(config('services.africas_talking.api_key')) &&
                                !empty(config('services.africas_talking.username')),
            'nexmo' => !empty(config('services.nexmo.api_key')) &&
                       !empty(config('services.nexmo.api_secret')),
            default => false
        };
    }

    /**
     * Check if Twilio is configured
     */
    protected function isTwilioConfigured(): bool
    {
        return !empty(config('services.twilio.sid')) &&
               !empty(config('services.twilio.token')) &&
               !empty(config('services.twilio.from'));
    }

    /**
     * Get the active provider name
     */
    public function getProvider(): string
    {
        return $this->provider;
    }
}
