<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Notifications\InvoiceCreated;

class InvoiceService
{
    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -6) + 1 : 1;
        
        return sprintf('INV-%s%s-%06d', $year, $month, $nextNumber);
    }

    /**
     * Create invoice with items
     */
    public function createInvoice(array $data, array $items): Invoice
    {
        // Generate invoice number if not provided
        if (!isset($data['invoice_number'])) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
        }

        // Calculate totals
        $subtotal = collect($items)->sum(fn($item) => $item['quantity'] * $item['unit_price']);
        $taxAmount = $data['tax_amount'] ?? ($subtotal * ($data['tax_rate'] ?? 0) / 100);
        $discountAmount = $data['discount_amount'] ?? 0;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $taxAmount;
        $data['discount_amount'] = $discountAmount;
        $data['total_amount'] = $totalAmount;
        $data['balance_amount'] = $totalAmount;
        $data['paid_amount'] = 0;
        $data['status'] = 'pending';

        // Create invoice
        $invoice = Invoice::create($data);

        // Create invoice items
        foreach ($items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'item_type' => $item['item_type'] ?? 'service',
                'reference_id' => $item['reference_id'] ?? null,
            ]);
        }

        return $invoice->load('items');
    }

    /**
     * Generate PDF for invoice
     */
    public function generatePdf(Invoice $invoice): \Barryvdh\DomPDF\PDF
    {
        $invoice->load(['patient', 'doctor', 'items', 'payments']);
        
        return Pdf::loadView('hms.billing.invoices.pdf', compact('invoice'));
    }

    /**
     * Send invoice via email
     */
    public function sendInvoiceEmail(Invoice $invoice): bool
    {
        try {
            $invoice->load(['patient', 'doctor', 'items']);
            
            // Generate PDF
            $pdf = $this->generatePdf($invoice);
            
            // Send email
            $invoice->patient->notify(new InvoiceCreated($invoice, $pdf->output()));
            
            // Update invoice
            $invoice->update(['email_sent_at' => now()]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send invoice email', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Apply payment to invoice
     */
    public function applyPayment(Invoice $invoice, float $amount, array $paymentData): bool
    {
        if ($amount > $invoice->balance_amount) {
            return false;
        }

        // Update invoice
        $invoice->paid_amount += $amount;
        $invoice->balance_amount -= $amount;
        
        // Update status
        if ($invoice->balance_amount <= 0) {
            $invoice->status = 'paid';
        } elseif ($invoice->paid_amount > 0) {
            $invoice->status = 'partial';
        }
        
        $invoice->save();

        return true;
    }

    /**
     * Add items to existing invoice
     */
    public function addItems(Invoice $invoice, array $items): Invoice
    {
        foreach ($items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'item_type' => $item['item_type'] ?? 'service',
            ]);
        }

        // Recalculate totals
        $this->recalculateTotals($invoice);

        return $invoice->fresh('items');
    }

    /**
     * Recalculate invoice totals
     */
    public function recalculateTotals(Invoice $invoice): void
    {
        $subtotal = $invoice->items()->sum('total_price');
        $taxAmount = $subtotal * ($invoice->tax_rate ?? 0) / 100;
        $totalAmount = $subtotal + $taxAmount - $invoice->discount_amount;

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'balance_amount' => $totalAmount - $invoice->paid_amount,
        ]);
    }

    /**
     * Apply discount to invoice
     */
    public function applyDiscount(Invoice $invoice, float $discountAmount, string $reason = null): Invoice
    {
        $invoice->update([
            'discount_amount' => $discountAmount,
            'discount_reason' => $reason,
        ]);

        $this->recalculateTotals($invoice);

        return $invoice->fresh();
    }

    /**
     * Get invoice statistics
     */
    public function getStatistics(array $filters = []): array
    {
        $query = Invoice::query();

        if (isset($filters['start_date'])) {
            $query->where('invoice_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('invoice_date', '<=', $filters['end_date']);
        }

        return [
            'total_invoices' => $query->count(),
            'total_amount' => $query->sum('total_amount'),
            'paid_amount' => $query->sum('paid_amount'),
            'pending_amount' => $query->sum('balance_amount'),
            'paid_invoices' => $query->where('status', 'paid')->count(),
            'partial_invoices' => $query->where('status', 'partial')->count(),
            'pending_invoices' => $query->where('status', 'pending')->count(),
            'overdue_invoices' => $query->where('status', '!=', 'paid')
                ->where('due_date', '<', now())
                ->count(),
        ];
    }
}


