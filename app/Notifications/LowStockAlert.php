<?php

namespace App\Notifications;

use App\Models\Medicine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lowStockItems;

    public function __construct($lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Low Stock Alert - ' . config('app.name'))
            ->greeting('Low Stock Alert')
            ->line('The following medicines are running low on stock:');

        foreach ($this->lowStockItems->take(15) as $medicine) {
            $mail->line("- {$medicine->name} - Current: {$medicine->stock_quantity}, Minimum: {$medicine->minimum_stock}");
        }

        if ($this->lowStockItems->count() > 15) {
            $mail->line("... and " . ($this->lowStockItems->count() - 15) . " more items.");
        }

        return $mail->action('View Inventory', url('/hms/inventory'))
            ->line('Please consider placing a purchase order to replenish stock.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'low_stock_alert',
            'count' => $this->lowStockItems->count(),
            'message' => "Low stock alert: {$this->lowStockItems->count()} medicines below reorder level.",
        ];
    }
}
