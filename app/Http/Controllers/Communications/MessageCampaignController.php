<?php

namespace App\Http\Controllers\Communications;

use App\Http\Controllers\Controller;
use App\Models\MessageCampaign;
use App\Models\OutboundMessage;
use App\Models\MessageOptOut;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageCampaignController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'channel' => 'required|string|in:sms,email,whatsapp',
            'template_id' => 'nullable|exists:message_templates,id',
            'target_audience' => 'required|array',
        ]);

        $campaign = MessageCampaign::create([
            'name' => $data['name'],
            'channel' => $data['channel'],
            'template_id' => $data['template_id'] ?? null,
            'target_audience' => $data['target_audience'],
            'status' => 'draft',
        ]);

        return response()->json([
            'success' => true,
            'data' => $campaign,
            'message' => 'Campaign created successfully'
        ], 201);
    }

    public function start(MessageCampaign $campaign): JsonResponse
    {
        if ($campaign->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Campaign cannot be started'
            ], 400);
        }

        $recipients = $this->resolveRecipients($campaign);
        $optedOutPhones = MessageOptOut::where('channel', $campaign->channel)
            ->pluck('phone')
            ->toArray();
        $optedOutEmails = MessageOptOut::where('channel', $campaign->channel)
            ->pluck('email')
            ->toArray();

        $total = 0;
        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            $phone = $recipient->phone ?? null;
            $email = $recipient->email ?? null;

            if ($phone && in_array($phone, $optedOutPhones)) {
                continue;
            }
            if ($email && in_array($email, $optedOutEmails)) {
                continue;
            }

            $total++;
            $message = OutboundMessage::create([
                'recipient_id' => $recipient->id,
                'recipient_phone' => $phone,
                'recipient_email' => $email,
                'channel' => $campaign->channel,
                'template_id' => $campaign->template_id,
                'body' => $campaign->template ? $campaign->template->content : '',
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            if ($message) {
                $sent++;
            } else {
                $failed++;
            }
        }

        $campaign->update([
            'status' => 'running',
            'started_by' => Auth::id(),
            'started_at' => now(),
            'total_recipients' => $total,
            'sent_count' => $sent,
            'failed_count' => $failed,
        ]);

        // Mark as completed immediately for simple campaigns
        if ($total > 0) {
            $campaign->update(['status' => 'completed', 'completed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'data' => $campaign,
            'message' => 'Campaign started successfully'
        ]);
    }

    private function resolveRecipients(MessageCampaign $campaign)
    {
        $audience = $campaign->target_audience;

        if (isset($audience['user_ids']) && is_array($audience['user_ids'])) {
            return User::whereIn('id', $audience['user_ids'])->get();
        }

        return User::where('email', '!=', null)->limit(100)->get();
    }
}
