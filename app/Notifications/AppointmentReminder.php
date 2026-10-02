<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Reminder - ' . $this->appointment->doctor->full_name ?? 'Doctor')
            ->view('emails.appointment-confirmation', [
                'patient' => $notifiable,
                'appointment' => $this->appointment,
            ])
            ->greeting('Hello ' . ($notifiable->first_name ?? $notifiable->name) . ',')
            ->line('This is a reminder about your upcoming appointment.')
            ->action('View Appointment', route('hms.appointments.index'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->full_name ?? 'N/A',
            'appointment_date' => $this->appointment->scheduled_at ?? $this->appointment->appointment_date,
            'type' => 'appointment_reminder',
            'message' => 'Reminder: Appointment with Dr. ' . ($this->appointment->doctor->full_name ?? 'N/A') . ' on ' . ($this->appointment->scheduled_at ?? $this->appointment->appointment_date)?->format('M d, Y'),
        ];
    }
}
