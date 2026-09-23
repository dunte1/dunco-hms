<?php

namespace App\Notifications;

use App\Models\MedicineBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockExpiryAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $expiringWeek;
    protected $expiringMonth;
    protected $expired;

    public function __construct($expiringWeek, $expiringMonth, $expired)
    {
        $this->expiringWeek = $expiringWeek;
        $this->expiringMonth = $expiringMonth;
        $this->expired = $expired;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Stock Expiry Alert - ' . config('app.name'))
            ->greeting('Stock Expiry Alert')
            ->line('The following stock items require attention due to expiry dates:');

        if ($this->expired->isNotEmpty()) {
            $mail->line("**EXPIRED ({$this->expired->count()} items):**");
            foreach ($this->expired->take(10) as $batch) {
                $mail->line("- {$batch->medicine->name} (Batch: {$batch->batch_number}) - Expired " . $batch->expiry_date->format('M d, Y') . " - Store: {$batch->store->name}");
            }
        }

        if ($this->expiringWeek->isNotEmpty()) {
            $mail->line("**Expiring within 1 week ({$this->expiringWeek->count()} items):**");
            foreach ($this->expiringWeek->take(10) as $batch) {
                $mail->line("- {$batch->medicine->name} (Batch: {$batch->batch_number}) - Expires " . $batch->expiry_date->format('M d, Y') . " - Store: {$batch->store->name}");
            }
        }

        if ($this->expiringMonth->isNotEmpty()) {
            $mail->line("**Expiring within 1 month ({$this->expiringMonth->count()} items):**");
            foreach ($this->expiringMonth->take(10) as $batch) {
                $mail->line("- {$batch->medicine->name} (Batch: {$batch->batch_number}) - Expires " . $batch->expiry_date->format('M d, Y') . " - Store: {$batch->store->name}");
            }
        }

        $total = $this->expired->count() + $this->expiringWeek->count() + $this->expiringMonth->count();
        if ($total > 10) {
            $mail->line("... and " . ($total - 10) . " more items.");
        }

        return $mail->action('View Stock Alerts', url('/hms/inventory/expiry-alerts'))
            ->line('Please review and take necessary action (dispose, write off, or discount).');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'stock_expiry_alert',
            'expired_count' => $this->expired->count(),
            'expiring_week_count' => $this->expiringWeek->count(),
            'expiring_month_count' => $this->expiringMonth->count(),
            'message' => "Stock expiry alert: {$this->expired->count()} expired, {$this->expiringWeek->count()} expiring in 1 week, {$this->expiringMonth->count()} expiring in 1 month.",
        ];
    }
}
