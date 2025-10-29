<?php

namespace App\Notifications;

use App\Models\LabRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LabResultReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $labRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(LabRequest $labRequest)
    {
        $this->labRequest = $labRequest;
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
            ->subject('Lab Results Ready - ' . $this->labRequest->request_number)
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . ',')
            ->line('Lab results for your test request are now ready.')
            ->line('Request Number: ' . $this->labRequest->request_number)
            ->line('Patient: ' . $this->labRequest->patient->full_name ?? 'N/A')
            ->line('Request Date: ' . $this->labRequest->request_date->format('M d, Y'))
            ->action('View Results', route('hms.lab.requests.show', $this->labRequest->id))
            ->line('Please log in to view the complete results.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'lab_result_ready',
            'lab_request_id' => $this->labRequest->id,
            'request_number' => $this->labRequest->request_number,
            'patient_name' => $this->labRequest->patient->full_name ?? 'N/A',
            'message' => 'Lab results are ready for request ' . $this->labRequest->request_number,
        ];
    }
}

