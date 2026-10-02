<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyDoctorOfAppointment implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment;
        
        // Notify doctor via email if available
        if ($appointment->doctor && $appointment->doctor->email) {
            try {
                \Mail::send('emails.appointment-confirmation', [
                    'patient' => $appointment->patient,
                    'appointment' => $appointment,
                ], function ($message) use ($appointment) {
                    $message->to($appointment->doctor->email)
                        ->subject('New Appointment - ' . ($appointment->scheduled_at?->format('M d, Y H:i') ?? 'Scheduled'))
                        ->from(\App\Models\SystemSetting::get('hospital_email', config('mail.from.address')), \App\Models\SystemSetting::get('hospital_name', config('app.name')));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment notification to doctor: ' . $e->getMessage());
            }
        }

        \Log::info('Appointment booked', [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id
        ]);
    }
}
