<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessagingController extends Controller
{
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
        $templates = [
            ['id' => 1, 'name' => 'Appointment Reminder', 'content' => 'Hello {patient_name}, this is a reminder for your appointment on {date} at {time}.'],
            ['id' => 2, 'name' => 'Payment Reminder', 'content' => 'Dear {patient_name}, your payment of ${amount} is due. Please settle your account.'],
            ['id' => 3, 'name' => 'Lab Results Ready', 'content' => 'Hello {patient_name}, your lab results are ready. Please visit the hospital to collect them.'],
        ];

        return view('hms.communication.messaging.templates', compact('templates'));
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient' => 'required|string',
            'message_type' => 'required|in:sms,email',
            'message' => 'required|string',
        ]);

        // Logic to send message would go here
        
        return back()->with('success', 'Message sent successfully!');
    }
}
