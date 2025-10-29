<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentsController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['invoice.patient', 'invoice.doctor'])->latest('payment_date')->paginate(15);
        return view('hms.billing.payments.index', compact('payments'));
    }

    public function create(): View
    {
        $invoices = Invoice::where('balance_amount', '>', 0)->with('patient')->get();
        return view('hms.billing.payments.create', compact('invoices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($data['invoice_id']);
        
        // Validate payment amount doesn't exceed balance
        if ($data['amount'] > $invoice->balance_amount) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed balance amount.']);
        }

        $data['patient_id'] = $invoice->patient_id;
        Payment::create($data);

        // Update invoice payment status
        $invoice->paid_amount += $data['amount'];
        $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
        $invoice->status = $invoice->balance_amount <= 0 ? 'paid' : ($invoice->paid_amount > 0 ? 'partial' : 'pending');
        $invoice->save();

        return redirect()->route('hms.billing.payments.index')->with('status', 'Payment recorded');
    }
    
    /**
     * Display thermal receipt for a payment
     */
    public function thermalReceipt(Payment $payment): View
    {
        $payment->load(['invoice.items', 'invoice.patient']);
        return view('hms.billing.payments.thermal-receipt', compact('payment'));
    }
    
    /**
     * Display thermal receipt for an invoice
     */
    public function invoiceThermalReceipt(Invoice $invoice): View
    {
        $invoice->load(['items', 'patient', 'payments']);
        return view('hms.billing.invoices.thermal-receipt', compact('invoice'));
    }
}
