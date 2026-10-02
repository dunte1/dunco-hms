<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * IPD final bill: aggregate admission-related charges into an invoice.
 * Uses bed-type daily charge x length of stay + any open lab invoices for the patient during stay.
 */
class IpdFinalBillController extends Controller
{
    public function show(IpdAdmission $admission): View
    {
        $admission->load(['patient', 'bed.bedType', 'ward', 'doctor']);
        $bill = $this->buildBill($admission);
        $existingInvoice = Invoice::where('notes', 'like', 'IPD Final Bill%' . $admission->admission_number)
            ->orWhere('invoice_number', 'like', 'INV-IPD-' . $admission->admission_number . '%')
            ->first();

        return view('hms.ipd.final-bill', compact('admission', 'bill', 'existingInvoice'));
    }

    public function generate(IpdAdmission $admission): RedirectResponse
    {
        $bill = $this->buildBill($admission);

        if ($bill['total'] <= 0) {
            return redirect()->route('hms.ipd.final-bill.show', $admission)
                ->with('error', 'No billable charges found for this admission.');
        }

        $invoice = Invoice::create([
            'invoice_number' => 'INV-IPD-' . $admission->admission_number,
            'patient_id' => $admission->patient_id,
            'doctor_id' => $admission->doctor_id ?? null,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'subtotal' => $bill['total'],
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => $bill['total'],
            'paid_amount' => 0,
            'balance_amount' => $bill['total'],
            'status' => 'pending',
            'notes' => 'IPD Final Bill ' . $admission->admission_number,
        ]);

        foreach ($bill['lines'] as $line) {
            $invoice->items()->create([
                'item_type' => $line['type'],
                'item_name' => $line['name'],
                'description' => $line['description'],
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'total_price' => $line['total'],
            ]);
        }

        \App\Models\AuditLog::log(
            'user',
            auth()->id(),
            'ipd.final-bill.generate',
            'IpdAdmission',
            $admission->id,
            null,
            ['invoice_id' => $invoice->id, 'total' => $bill['total']],
            'IPD final bill generated'
        );

        return redirect()->route('hms.ipd.final-bill.show', $admission)
            ->with('success', "Final bill {$invoice->invoice_number} created ({$bill['lines_count']} lines).");
    }

    /**
     * @return array{lines: array<int, array<string, mixed>>, total: float, lines_count: int, los: int, bed_rate: float}
     */
    protected function buildBill(IpdAdmission $admission): array
    {
        $lines = [];
        $los = max(1, $admission->getDurationOfStayAttribute() ?? 1);
        $bedRate = (float) optional(optional($admission->bed)->bedType)->charge_per_day;

        if ($bedRate > 0) {
            $lines[] = [
                'type' => 'bed_charge',
                'name' => 'Bed charges',
                'description' => "{$bedRate}/day x {$los} day(s)",
                'quantity' => $los,
                'unit_price' => $bedRate,
                'total' => round($bedRate * $los, 2),
            ];
        }

        // Open lab invoices already created for this patient during stay window
        $admitDate = $admission->admission_date ?? $admission->created_at->toDateString();
        $labInvoices = Invoice::where('patient_id', $admission->patient_id)
            ->where('invoice_number', 'like', 'INV-LAB-%')
            ->where('invoice_date', '>=', $admitDate)
            ->get();

        foreach ($labInvoices as $inv) {
            $lines[] = [
                'type' => 'lab_invoice',
                'name' => 'Laboratory ' . $inv->invoice_number,
                'description' => 'Linked lab invoice during stay',
                'quantity' => 1,
                'unit_price' => (float) $inv->total_amount,
                'total' => (float) $inv->total_amount,
            ];
        }

        return [
            'lines' => $lines,
            'total' => round(array_sum(array_column($lines, 'total')), 2),
            'lines_count' => count($lines),
            'los' => $los,
            'bed_rate' => $bedRate,
        ];
    }
}
