<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
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
        $daysOverdue = now()->diffInDays($this->invoice->due_date, false);
        $isOverdue = $daysOverdue < 0;
        
        $message = (new MailMessage)
            ->subject($isOverdue ? 'Overdue Payment Notice' : 'Payment Reminder')
            ->greeting('Hello ' . $notifiable->first_name . ',');

        if ($isOverdue) {
            $message->line('Your payment is ' . abs($daysOverdue) . ' days overdue.');
            $message->line('Please make payment immediately to avoid additional charges.');
        } else {
            $message->line('This is a friendly reminder that your payment is due soon.');
            $message->line('Due Date: ' . $this->invoice->due_date->format('M d, Y'));
        }

        return $message
            ->line('Invoice Number: ' . $this->invoice->invoice_number)
            ->line('Amount Due: $' . number_format($this->invoice->balance_amount, 2))
            ->action('Pay Now', route('hms.billing.invoices.show', $this->invoice->id))
            ->line('If you have already made payment, please disregard this notice.')
            ->line('Thank you for your cooperation.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $daysOverdue = now()->diffInDays($this->invoice->due_date, false);
        
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'balance_amount' => $this->invoice->balance_amount,
            'due_date' => $this->invoice->due_date,
            'days_overdue' => max(0, abs($daysOverdue)),
            'is_overdue' => $daysOverdue < 0,
            'type' => 'payment_reminder',
            'message' => 'Payment reminder for invoice #' . $this->invoice->invoice_number . ' - $' . number_format($this->invoice->balance_amount, 2),
        ];
    }
}
