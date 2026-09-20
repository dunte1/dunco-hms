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
        return (new MailMessage)
            ->subject('Payment Receipt - Thank You!')
            ->view('emails.payment-receipt', ['payment' => $this->payment]);
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
