<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendAppointmentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        // Get appointments scheduled for tomorrow
        $tomorrow = Carbon::tomorrow();
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('scheduled_at', $tomorrow)
            ->whereIn('status', ['confirmed', 'scheduled'])
            ->get();

        foreach ($appointments as $appointment) {
            // Send email notification
            if ($appointment->patient && $appointment->patient->email) {
                $appointment->patient->notify(new AppointmentReminder($appointment));
            }

            // Send SMS reminder if configured
            if ($appointment->patient && $appointment->patient->phone && $smsService->isConfigured()) {
                $message = "Reminder: You have an appointment with Dr. {$appointment->doctor->full_name} tomorrow at {$appointment->scheduled_at->format('h:i A')}. Please arrive 10 minutes early.";
                $smsService->send($appointment->patient->phone, $message);
            }
        }
    }
}

