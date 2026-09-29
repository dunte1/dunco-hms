<?php

namespace App\Http\Controllers\Communications;

use App\Http\Controllers\Controller;
use App\Models\OutboundMessage;
use App\Models\MessageOptOut;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OutboundMessageController extends Controller
{
    public function __construct(
        protected SmsService $smsService
    ) {}

    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'recipient_id' => 'nullable|exists:users,id',
            'recipient_phone' => 'nullable|string',
            'recipient_email' => 'nullable|email',
            'channel' => 'required|string|in:sms,email,whatsapp',
            'template_id' => 'nullable|exists:message_templates,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        // Check opt-out
        if ($this->isOptedOut($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Recipient has opted out of this channel'
            ], 422);
        }

        $message = OutboundMessage::create([
            'recipient_id' => $data['recipient_id'] ?? null,
            'recipient_phone' => $data['recipient_phone'] ?? null,
            'recipient_email' => $data['recipient_email'] ?? null,
            'channel' => $data['channel'],
            'template_id' => $data['template_id'] ?? null,
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'],
            'status' => 'queued',
        ]);

        // Attempt to send via SMS service if channel is sms
        if ($data['channel'] === 'sms' && !empty($data['recipient_phone'])) {
            $result = $this->smsService->send($data['recipient_phone'], $data['body']);

            if ($result['success']) {
                $message->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'provider_message_id' => $result['message_id'] ?? null,
                ]);
            } else {
                $message->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'error_message' => $result['message'] ?? 'Unknown error',
                ]);
            }
        } else {
            // For email/whatsapp, mark as sent (queue would handle actual sending)
            $message->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message queued for delivery'
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $query = OutboundMessage::with(['recipient', 'template']);

        if ($request->has('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    private function isOptedOut(array $data): bool
    {
        $query = MessageOptOut::where('channel', $data['channel']);

        if (!empty($data['recipient_phone'])) {
            $query->where('phone', $data['recipient_phone']);
        } elseif (!empty($data['recipient_email'])) {
            $query->where('email', $data['recipient_email']);
        } else {
            return false;
        }

        return $query->exists();
    }
}
