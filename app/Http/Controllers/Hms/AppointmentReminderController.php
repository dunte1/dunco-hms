<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentReminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppointmentReminderController extends Controller
{
    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate([
            'reminder_type' => 'required|in:sms,email,whatsapp',
            'send_at' => 'required|date|after:now',
        ]);

        $appointment->reminders()->create([
            'reminder_type' => $data['reminder_type'],
            'send_at' => $data['send_at'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Appointment reminder scheduled!');
    }

    public function send(AppointmentReminder $reminder): RedirectResponse
    {
        $reminder->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Reminder marked as sent!');
    }
}
