<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];
        
        // Add WhatsApp if Twilio is configured
        if (config('services.twilio.sid') && config('services.twilio.token')) {
            // Note: WhatsApp will be sent directly from controller
            // This ensures instant delivery
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $invoice = $this->payment->invoice;
        
        return (new MailMessage)
            ->subject('Payment Received - Thank You!')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('We have received your payment. Thank you!')
            ->line('Payment Amount: ' . ($invoice->currency_symbol ?? config('app.currency_symbol', 'KSh')) . ' ' . number_format($this->payment->amount, 2))
            ->line('Payment Method: ' . ucwords(str_replace('_', ' ', $this->payment->payment_method)))
            ->line('Invoice: ' . $invoice->invoice_number)
            ->line('Payment Reference: ' . ($this->payment->payment_reference ?? 'N/A'))
            ->line('Remaining Balance: ' . ($invoice->currency_symbol ?? config('app.currency_symbol', 'KSh')) . ' ' . number_format($invoice->balance_amount, 2))
            ->action('View Receipt', route('hms.billing.payments.index'))
            ->line('Thank you for your prompt payment!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'payment_method' => $this->payment->payment_method,
            'invoice_number' => $this->payment->invoice->invoice_number,
            'type' => 'payment_received',
            'message' => 'Payment of $' . number_format($this->payment->amount, 2) . ' received successfully',
        ];
    }
}
