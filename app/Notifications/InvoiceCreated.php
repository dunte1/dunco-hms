<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;
    public $pdfContent;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice, $pdfContent = null)
    {
        $this->invoice = $invoice;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $message = (new MailMessage)
            ->subject('Invoice #' . $this->invoice->invoice_number)
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('A new invoice has been created for you.')
            ->line('Invoice Number: ' . $this->invoice->invoice_number)
            ->line('Amount: $' . number_format($this->invoice->total_amount, 2))
            ->line('Due Date: ' . $this->invoice->due_date->format('M d, Y'))
            ->action('View Invoice', route('hms.billing.invoices.show', $this->invoice->id))
            ->line('Please make payment before the due date to avoid late fees.')
            ->line('Thank you for choosing our services!');

        // Attach PDF if available
        if ($this->pdfContent) {
            $message->attachData($this->pdfContent, 'invoice-' . $this->invoice->invoice_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->total_amount,
            'due_date' => $this->invoice->due_date,
            'type' => 'invoice_created',
            'message' => 'New invoice #' . $this->invoice->invoice_number . ' created for $' . number_format($this->invoice->total_amount, 2),
        ];
    }
}
