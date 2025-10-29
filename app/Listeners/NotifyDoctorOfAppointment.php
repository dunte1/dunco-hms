<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Notifications\AppointmentReminder;
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
                \Mail::send([], [], function ($message) use ($appointment) {
                    $message->to($appointment->doctor->email)
                        ->subject('New Appointment - ' . $appointment->scheduled_at->format('M d, Y H:i'))
                        ->html('You have a new appointment with ' . $appointment->patient->full_name . ' on ' . $appointment->scheduled_at->format('l, F j, Y') . ' at ' . $appointment->scheduled_at->format('h:i A'));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment notification to doctor: ' . $e->getMessage());
            }
        }

        // Notify receptionist (could be enhanced to find on-duty receptionists)
        // For now, just log
        \Log::info('Appointment booked', [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id
        ]);
    }
}

