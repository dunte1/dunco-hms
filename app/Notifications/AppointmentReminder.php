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
            ->subject('Appointment Reminder')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('This is a reminder about your upcoming appointment.')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->full_name)
            ->line('Date: ' . $this->appointment->appointment_date->format('l, F j, Y'))
            ->line('Time: ' . $this->appointment->appointment_time)
            ->line('Department: ' . $this->appointment->doctor->department->name ?? 'N/A')
            ->action('View Details', route('hms.appointments.index'))
            ->line('Please arrive 10 minutes early.')
            ->line('If you need to reschedule, please contact us as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->full_name,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'type' => 'appointment_reminder',
            'message' => 'Reminder: Appointment with Dr. ' . $this->appointment->doctor->full_name . ' on ' . $this->appointment->appointment_date->format('M d, Y'),
        ];
    }
}
