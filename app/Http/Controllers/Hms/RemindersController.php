<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RemindersController extends Controller
{
    public function index(): View
    {
        $upcomingAppointments = Appointment::with('patient')
            ->where('scheduled_at', '>', now())
            ->where('scheduled_at', '<', now()->addDays(7))
            ->count();

        $pendingPayments = Invoice::where('status', 'pending')->count();

        return view('hms.communication.reminders.index', compact('upcomingAppointments', 'pendingPayments'));
    }

    public function appointments(): View
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('scheduled_at', '>', now())
            ->where('scheduled_at', '<', now()->addDays(7))
            ->orderBy('scheduled_at')
            ->paginate(20);

        return view('hms.communication.reminders.appointments', compact('appointments'));
    }

    public function payments(): View
    {
        $invoices = Invoice::with('patient')
            ->where('status', 'pending')
            ->orWhere('status', 'partial')
            ->orderBy('invoice_date', 'desc')
            ->paginate(20);

        return view('hms.communication.reminders.payments', compact('invoices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reminder_type' => 'required|in:appointment,payment',
            'recipient_id' => 'required|integer',
            'message' => 'required|string',
            'send_via' => 'required|in:email,sms,both',
        ]);

        // Logic to send reminder would go here

        return back()->with('success', 'Reminder sent successfully!');
    }
}
