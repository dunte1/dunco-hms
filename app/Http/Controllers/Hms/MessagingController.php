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
        $patients = Patient::forSelect();
        $templates = MessageTemplate::where('type', 'sms')->orderBy('name')->get();
        return view('hms.communication.messaging.bulk', compact('patients', 'templates'));
    }

    public function templates(): View
    {
        $templates = MessageTemplate::orderBy('name')->get();

        return view('hms.communication.messaging.templates', compact('templates'));
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        MessageTemplate::updateOrCreate(
            ['name' => $data['name'], 'type' => 'sms'],
            ['content' => $data['content']]
        );

        return back()->with('success', 'Template saved successfully!');
    }

    public function destroyTemplate(MessageTemplate $template): RedirectResponse
    {
        $template->delete();

        return back()->with('success', 'Template deleted.');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient' => 'required|string',
            'message_type' => 'required|in:sms,email',
            'message' => 'required|string',
            'patients' => 'nullable|array',
            'patients.*' => 'integer|exists:patients,id',
        ]);

        if ($data['message_type'] === 'sms') {
            $recipients = [];

            if (!empty($data['patients'])) {
                $recipients = Patient::whereIn('id', $data['patients'])
                    ->pluck('phone')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            } elseif (!empty($data['recipient'])) {
                $recipients = [$data['recipient']];
            }

            if (empty($recipients)) {
                return back()->with('error', 'No valid recipients found.');
            }

            $result = count($recipients) === 1
                ? $this->smsService->send($recipients[0], $data['message'])
                : $this->smsService->sendBulk($recipients, $data['message']);

            if ($result['success']) {
                $sent = $result['success_count'] ?? 1;
                return back()->with('success', "SMS sent successfully to {$sent} recipient(s)!");
            }

            return back()->with('error', 'Failed to send SMS: ' . ($result['message'] ?? 'Unknown error'));
        }

        return back()->with('success', 'Message queued for sending!');
    }
}
