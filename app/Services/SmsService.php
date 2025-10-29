<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class SmsService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid', env('TWILIO_SID'));
        $token = config('services.twilio.token', env('TWILIO_TOKEN'));
        $this->from = config('services.twilio.from', env('TWILIO_FROM'));

        if ($sid && $token) {
            try {
                $this->client = new Client($sid, $token);
            } catch (\Exception $e) {
                Log::error('SMS Service initialization failed: ' . $e->getMessage());
                $this->client = null;
            }
        }
    }

    /**
     * Send SMS to a single recipient
     */
    public function send(string $to, string $message): bool
    {
        if (!$this->client || !$this->from) {
            Log::warning('SMS service not configured. Message not sent to: ' . $to);
            return false;
        }

        try {
            // Remove any non-numeric characters except +
            $to = preg_replace('/[^0-9+]/', '', $to);

            $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            Log::info('SMS sent successfully to: ' . $to);
            return true;
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage(), [
                'to' => $to,
                'message' => substr($message, 0, 50) . '...'
            ]);
            return false;
        }
    }

    /**
     * Send SMS to multiple recipients
     */
    public function sendBulk(array $recipients, string $message): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        foreach ($recipients as $recipient) {
            $success = $this->send($recipient, $message);
            
            if ($success) {
                $results['success']++;
                $results['details'][] = ['recipient' => $recipient, 'status' => 'success'];
            } else {
                $results['failed']++;
                $results['details'][] = ['recipient' => $recipient, 'status' => 'failed'];
            }
        }

        return $results;
    }

    /**
     * Send SMS with template replacement
     */
    public function sendWithTemplate(string $to, string $template, array $variables): bool
    {
        $message = $this->replaceTemplateVariables($template, $variables);
        return $this->send($to, $message);
    }

    /**
     * Replace template variables like {patient_name}, {date}, etc.
     */
    protected function replaceTemplateVariables(string $template, array $variables): string
    {
        $message = $template;
        foreach ($variables as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }

    /**
     * Check if SMS service is configured
     */
    public function isConfigured(): bool
    {
        return $this->client !== null && $this->from !== null;
    }
}

