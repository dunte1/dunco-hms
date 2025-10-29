<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoicesController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with(['patient', 'doctor'])->latest('invoice_date')->paginate(10);
        return view('hms.billing.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email', 'phone']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        
        // Common services/items for quick selection
        $services = [
            ['name' => 'General Consultation', 'price' => 1500],
            ['name' => 'Specialist Consultation', 'price' => 2500],
            ['name' => 'Follow-up Visit', 'price' => 1000],
            ['name' => 'Emergency Consultation', 'price' => 3000],
            ['name' => 'Lab Test - CBC', 'price' => 800],
            ['name' => 'Lab Test - Blood Sugar', 'price' => 500],
            ['name' => 'Lab Test - Urinalysis', 'price' => 600],
            ['name' => 'X-Ray - Chest', 'price' => 2000],
            ['name' => 'X-Ray - Limb', 'price' => 1800],
            ['name' => 'Ultrasound Scan', 'price' => 3500],
            ['name' => 'ECG', 'price' => 1200],
            ['name' => 'Minor Procedure', 'price' => 2500],
            ['name' => 'Dressing/Wound Care', 'price' => 800],
            ['name' => 'Injection/IV', 'price' => 500],
            ['name' => 'Bed Charges (per day)', 'price' => 2000],
        ];
        
        return view('hms.billing.invoices.create', compact('patients', 'doctors', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.item_type' => 'nullable|string',
        ]);

        // Generate invoice number
        $year = date('Y');
        $month = date('m');
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -6) + 1 : 1;
        $data['invoice_number'] = sprintf('INV-%s%s-%06d', $year, $month, $nextNumber);
        
        // Calculate totals
        $data['total_amount'] = $data['subtotal'] + ($data['tax_amount'] ?? 0) - ($data['discount_amount'] ?? 0);
        $data['balance_amount'] = $data['total_amount'];
        $data['paid_amount'] = 0;
        $data['status'] = 'pending';

        $invoice = Invoice::create($data);
        
        // Create invoice items
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'item_type' => $item['item_type'] ?? 'service',
            ]);
        }

        return redirect()->route('hms.billing.invoices.show', $invoice)
            ->with('status', 'Invoice created successfully');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['patient', 'doctor', 'items', 'payments']);
        return view('hms.billing.invoices.show', compact('invoice'));
    }

    public function generatePdf(Invoice $invoice)
    {
        $invoiceService = app(\App\Services\InvoiceService::class);
        $pdf = $invoiceService->generatePdf($invoice);
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function sendEmail(Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        $invoiceService = app(\App\Services\InvoiceService::class);
        $success = $invoiceService->sendInvoiceEmail($invoice);
        
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice emailed successfully to ' . $invoice->patient->email
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to send invoice email'
        ], 500);
    }

    public function edit(Invoice $invoice): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        
        $invoice->load('items');
        
        return view('hms.billing.invoices.edit', compact('invoice', 'patients', 'doctors'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($data);
        
        // Recalculate totals using service
        $invoiceService = app(\App\Services\InvoiceService::class);
        $invoiceService->recalculateTotals($invoice);

        return redirect()->route('hms.billing.invoices.show', $invoice)
            ->with('status', 'Invoice updated successfully');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        // Only allow deletion if no payments made
        if ($invoice->paid_amount > 0) {
            return back()->withErrors(['error' => 'Cannot delete invoice with payments. Please refund payments first.']);
        }

        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('hms.billing.invoices.index')
            ->with('status', 'Invoice deleted successfully');
    }
}
