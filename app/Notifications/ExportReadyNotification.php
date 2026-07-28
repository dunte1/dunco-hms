<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $filename;
    public $format;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $filename, string $format)
    {
        $this->filename = $filename;
        $this->format = $format;
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
            ->subject('Your Export is Ready')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your batch export has been completed successfully.')
            ->line('Format: ' . strtoupper($this->format))
            ->line('The file is available for download.')
            ->action('Download Export', url('/downloads/' . $this->filename))
            ->line('This link will expire in 7 days.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'export_ready',
            'filename' => $this->filename,
            'format' => $this->format,
            'message' => 'Your ' . strtoupper($this->format) . ' export is ready for download.',
        ];
    }
}

