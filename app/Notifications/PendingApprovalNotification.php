<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $approvable;
    protected $type;

    public function __construct($approvable, string $type)
    {
        $this->approvable = $approvable;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = str_replace('_', ' ', ucfirst($this->type));
        $modelLabel = class_basename($this->approvable);
        $identifier = $this->approvable->requisition_number
            ?? $this->approvable->adjustment_number
            ?? $this->approvable->po_number
            ?? '#' . $this->approvable->id;

        $mail = (new MailMessage)
            ->subject("Pending Approval Required - {$typeLabel}")
            ->greeting("Action Required: {$typeLabel}")
            ->line("A new {$typeLabel} is awaiting your approval.")
            ->line("Reference: {$identifier}");

        $details = match ($this->type) {
            'leave_request' => "Employee: {$this->approvable->employee->full_name ?? 'N/A'}, Type: {$this->approvable->leaveType->name ?? 'N/A'}, Duration: {$this->approvable->start_date} to {$this->approvable->end_date}",
            'requisition' => "Requested by: {$this->approvable->requester->name ?? 'N/A'}, Store: {$this->approvable->requestingStore->name ?? 'N/A'}",
            'purchase_order' => "Supplier: {$this->approvable->supplier->name ?? 'N/A'}, Amount: KES " . number_format($this->approvable->total_amount, 2),
            'stock_adjustment' => "Medicine: {$this->approvable->medicine->name ?? 'N/A'}, Adjustment: {$this->approvable->quantity_adjustment}, Store: {$this->approvable->store->name ?? 'N/A'}",
            default => "Please review and approve this request.",
        };

        $mail->line($details);

        $approveUrl = url("/hms/{$this->type}s/{$this->approvable->id}/approve");
        $rejectUrl = url("/hms/{$this->type}s/{$this->approvable->id}/reject");

        return $mail
            ->action('Review & Approve', $approveUrl)
            ->line('You can also reject this request from the review page.');
    }

    public function toArray(object $notifiable): array
    {
        $typeLabel = str_replace('_', ' ', ucfirst($this->type));
        $identifier = $this->approvable->requisition_number
            ?? $this->approvable->adjustment_number
            ?? $this->approvable->po_number
            ?? '#' . $this->approvable->id;

        return [
            'type' => $this->type,
            'model_type' => class_basename($this->approvable),
            'model_id' => $this->approvable->id,
            'message' => "Pending {$typeLabel} requires approval: {$identifier}",
        ];
    }
}
