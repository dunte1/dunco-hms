<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Notifications\PaymentReminder;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendPaymentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        // Get overdue invoices
        $overdueInvoices = Invoice::with('patient')
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueInvoices as $invoice) {
            if ($invoice->patient && $invoice->patient->email) {
                $invoice->patient->notify(new PaymentReminder($invoice));
            }

            // Send SMS reminder if configured
            if ($invoice->patient && $invoice->patient->phone && $smsService->isConfigured()) {
                $amount = number_format($invoice->total_amount - $invoice->paid_amount, 2);
                $message = "Payment Reminder: You have an outstanding balance of {$invoice->currency_symbol}{$amount} on invoice {$invoice->invoice_number}. Due date: {$invoice->due_date->format('M d, Y')}.";
                $smsService->send($invoice->patient->phone, $message);
            }
        }
    }
}

