<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MessageTemplate;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessagingController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index(): View
    {
        return view('hms.communication.messaging.index');
    }

    public function bulk(): View
    {
        $patients = Patient::orderBy('first_name')->get();
        return view('hms.communication.messaging.bulk', compact('patients'));
    }

    public function templates(): View
    {
        $templates = MessageTemplate::orderBy('name')->get();

        return view('hms.communication.messaging.templates', compact('templates'));
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient' => 'required|string',
            'message_type' => 'required|in:sms,email',
            'message' => 'required|string',
        ]);

        if ($data['message_type'] === 'sms') {
            $result = $this->smsService->send($data['recipient'], $data['message']);

            if ($result['success']) {
                return back()->with('success', 'SMS sent successfully!');
            }

            return back()->with('error', 'Failed to send SMS: ' . ($result['message'] ?? 'Unknown error'));
        }

        return back()->with('success', 'Message queued for sending!');
    }
}
