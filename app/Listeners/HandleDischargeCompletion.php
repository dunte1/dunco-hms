<?php

namespace App\Listeners;

use App\Events\DischargeCompleted;
use App\Models\Bed;
use App\Services\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleDischargeCompletion implements ShouldQueue
{
    use InteractsWithQueue;

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Handle the event.
     */
    public function handle(DischargeCompleted $event): void
    {
        $admission = $event->admission;

        // Mark bed as available
        if ($admission->bed_id) {
            Bed::where('id', $admission->bed_id)->update([
                'status' => 'available',
                'patient_id' => null
            ]);
        }

        // Generate invoice if not already generated
        if (!$admission->invoice_id && $admission->patient) {
            try {
                // Create invoice items from admission charges
                $items = [
                    [
                        'description' => 'Bed charges - ' . $admission->days_stayed . ' days',
                        'quantity' => $admission->days_stayed ?? 1,
                        'unit_price' => 1000, // This should come from bed type or settings
                    ],
                ];

                // Add any additional charges
                $invoiceData = [
                    'patient_id' => $admission->patient_id,
                    'invoice_date' => now(),
                    'due_date' => now()->addDays(7),
                    'status' => 'pending',
                ];

                $invoice = $this->invoiceService->createInvoice($invoiceData, $items);
                $admission->update(['invoice_id' => $invoice->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to generate invoice on discharge: ' . $e->getMessage());
            }
        }

        // Log discharge
        \Log::info('Discharge completed', [
            'admission_id' => $admission->id,
            'patient_id' => $admission->patient_id,
            'bed_id' => $admission->bed_id
        ]);
    }
}

