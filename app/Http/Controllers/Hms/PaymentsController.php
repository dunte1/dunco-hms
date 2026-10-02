<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Refund;
use App\Models\AuditLog;
use App\Notifications\PaymentReceived;
use App\Services\PaymentGatewayService;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $paymentGatewayService,
        protected SmsService $smsService
    ) {}

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
        
        // Process payment through gateway if needed
        if (in_array($data['payment_method'], ['stripe', 'paypal', 'mpesa'])) {
            $gatewayResult = $this->paymentGatewayService->processPayment(
                $invoice,
                $data['amount'],
                $data['payment_method'],
                $data
            );

            if (!$gatewayResult['success']) {
                return back()->withErrors(['payment_method' => $gatewayResult['message'] ?? 'Payment processing failed']);
            }

            // Store transaction ID
            if (isset($gatewayResult['transaction_id'])) {
                $data['payment_reference'] = $gatewayResult['transaction_id'];
            }

            // For M-Pesa, payment is pending until callback
            if ($data['payment_method'] === 'mpesa' && isset($gatewayResult['status']) && $gatewayResult['status'] === 'pending') {
                $data['status'] = 'pending';
                // Don't update invoice yet for pending M-Pesa payments - wait for callback
            }
        }

        $payment = Payment::create($data);

        \App\Models\AuditLog::log('user', auth()->id(), 'payment_recorded', 'Payment', $payment->id, null, $payment->toArray(), 'Payment recorded: $' . number_format($payment->amount));

        // Update invoice payment status automatically (only for non-pending payments)
        if (!isset($data['status']) || $data['status'] !== 'pending') {
            $invoice->paid_amount += $data['amount'];
            $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
            $invoice->status = $invoice->balance_amount <= 0 ? 'paid' : ($invoice->paid_amount > 0 ? 'partial' : 'pending');
            $invoice->save();
            
            Log::info('Invoice status updated automatically after payment', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status,
                'balance_amount' => $invoice->balance_amount,
                'payment_method' => $data['payment_method']
            ]);

            // Post payment to income + journal ledger (double-entry)
            $this->postPaymentToLedger($payment, $invoice, $data);
        } else {
            Log::info('M-Pesa payment created as pending, invoice will be updated on callback', [
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id
            ]);
        }

        // Send receipt notifications for completed payments
        if (!isset($data['status']) || $data['status'] !== 'pending') {
            $this->sendReceiptNotifications($payment);
        }

        $message = $data['payment_method'] === 'mpesa' 
            ? 'M-Pesa payment initiated. Please check your phone and complete the payment.'
            : 'Payment recorded successfully.';

        return redirect()->route('hms.billing.payments.index')->with('status', $message);
    }

    public function show(Payment $payment): View
    {
        $payment->load(['invoice.patient', 'invoice.doctor']);
        return view('hms.billing.payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        $invoices = Invoice::where('balance_amount', '>', 0)->with('patient')->get();
        return view('hms.billing.payments.edit', compact('payment', 'invoices'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment->update($data);
        return redirect()->route('hms.billing.payments.index')->with('status', 'Payment updated');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        // Reverse invoice balance
        $invoice = $payment->invoice;
        if ($invoice) {
            $invoice->paid_amount -= $payment->amount;
            $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
            $invoice->status = $invoice->balance_amount <= 0 ? 'paid' : ($invoice->paid_amount > 0 ? 'partial' : 'pending');
            $invoice->save();
        }

        $payment->delete();
        return redirect()->route('hms.billing.payments.index')->with('status', 'Payment deleted');
    }
    
    public function requestRefund(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        if ($data['amount'] > $payment->amount) {
            return back()->withErrors(['amount' => 'Refund amount cannot exceed payment amount.']);
        }

        $refund = Refund::create([
            'payment_id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'amount' => $data['amount'],
            'reason' => $data['reason'],
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        AuditLog::log('user', auth()->id(), 'refund_requested', 'Refund', $refund->id, null, $refund->toArray(), 'Refund requested: $' . number_format($refund->amount));

        return back()->with('status', 'Refund request submitted for approval');
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

    /**
     * Send receipt notifications via email and WhatsApp
     */
    protected function sendReceiptNotifications(Payment $payment): void
    {
        $patient = $payment->patient;
        $invoice = $payment->invoice;

        if (!$patient) {
            return;
        }

        // Send email receipt
        if ($patient->email) {
            try {
                $patient->notify(new PaymentReceived($payment));
                Log::info('Payment receipt email sent', [
                    'payment_id' => $payment->id,
                    'patient_email' => $patient->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send payment receipt email', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Send WhatsApp receipt
        if ($patient->phone) {
            $this->sendWhatsAppReceipt($patient, $payment, $invoice);
        }

        // Send SMS receipt as backup
        if ($patient->phone && $this->smsService) {
            $this->sendSmsReceipt($patient, $payment, $invoice);
        }
    }

    /**
     * Send receipt via WhatsApp
     */
    protected function sendWhatsAppReceipt($patient, Payment $payment, Invoice $invoice): void
    {
        try {
            // Format phone number (remove + and spaces, add country code if needed)
            $phone = preg_replace('/[^0-9]/', '', $patient->phone);
            if (!str_starts_with($phone, '254') && strlen($phone) == 9) {
                $phone = '254' . $phone;
            }

            $message = $this->formatReceiptMessage($payment, $invoice);

            // Use Twilio WhatsApp API (if configured)
            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $whatsappFrom = config('services.twilio.whatsapp_from', 'whatsapp:+14155238886');

            if ($twilioSid && $twilioToken) {
                $whatsappTo = 'whatsapp:+' . $phone;

                $response = \Illuminate\Support\Facades\Http::withBasicAuth($twilioSid, $twilioToken)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$twilioSid}/Messages.json", [
                        'From' => $whatsappFrom,
                        'To' => $whatsappTo,
                        'Body' => $message
                    ]);

                if ($response->successful()) {
                    Log::info('WhatsApp receipt sent', [
                        'payment_id' => $payment->id,
                        'phone' => $phone
                    ]);
                } else {
                    Log::warning('WhatsApp receipt failed', [
                        'payment_id' => $payment->id,
                        'response' => $response->body()
                    ]);
                }
            } else {
                Log::info('WhatsApp not configured, skipping WhatsApp receipt', [
                    'payment_id' => $payment->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp receipt', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Post a completed payment to Income + double-entry Journal.
     * Cash/bank accounts are resolved from Account model by type/name when possible.
     */
    protected function postPaymentToLedger(Payment $payment, Invoice $invoice, array $data): void
    {
        try {
            $amount = (float) $payment->amount;
            if ($amount <= 0) {
                return;
            }

            $method = strtolower((string) ($data['payment_method'] ?? 'cash'));
            $cashAccount = \App\Models\Account::where('name', 'like', '%Cash%')->first()
                ?? \App\Models\Account::where('slug', 'cash')->first()
                ?? \App\Models\Account::first();
            $incomeAccount = \App\Models\Account::where('name', 'like', '%Revenue%')->first()
                ?? \App\Models\Account::where('name', 'like', '%Income%')->first()
                ?? $cashAccount;

            $income = \App\Models\Income::create([
                'income_number' => 'INC-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
                'account_id' => $incomeAccount?->id,
                'income_category' => 'patient_payment',
                'source' => 'invoice',
                'patient_id' => $invoice->patient_id,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'amount' => $amount,
                'income_date' => $payment->payment_date ?? now()->toDateString(),
                'payment_method' => $method,
                'reference_number' => $payment->payment_reference ?? $payment->id,
                'description' => "Payment {$payment->id} against invoice {$invoice->invoice_number}",
                'recorded_by' => auth()->id(),
            ]);

            $entry = \App\Models\JournalEntry::create([
                'entry_number' => 'JE-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
                'date' => $payment->payment_date ?? now()->toDateString(),
                'description' => "Patient payment {$payment->id} / {$invoice->invoice_number}",
                'reference_type' => 'payment',
                'reference_id' => $payment->id,
                'status' => 'posted',
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);

            if ($cashAccount && $incomeAccount) {
                $entry->lines()->create([
                    'account_id' => $cashAccount->id,
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => "Cash/bank received ({$method})",
                ]);
                $entry->lines()->create([
                    'account_id' => $incomeAccount->id,
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => "Patient revenue {$invoice->invoice_number}",
                ]);
            }

            \Illuminate\Support\Facades\Log::info('Payment posted to ledger', [
                'payment_id' => $payment->id,
                'income_id' => $income->id,
                'journal_entry_id' => $entry->id,
            ]);
        } catch (\Exception $e) {
            // Ledger posting must not block payment capture; log for finance follow-up
            \Illuminate\Support\Facades\Log::error('Payment ledger post failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send receipt via SMS
     */
    protected function sendSmsReceipt($patient, Payment $payment, Invoice $invoice): void
    {
        try {
            $message = $this->formatReceiptMessage($payment, $invoice);
            
            $result = $this->smsService->send($patient->phone, $message);
            
            if ($result['success']) {
                Log::info('SMS receipt sent', [
                    'payment_id' => $payment->id,
                    'phone' => $patient->phone
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send SMS receipt', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Format receipt message
     */
    protected function formatReceiptMessage(Payment $payment, Invoice $invoice): string
    {
        $currency = $invoice->currency ?? 'KES';
        $symbol = $invoice->currency_symbol ?? 'KSh';
        
        return "📧 Payment Receipt\n\n" .
               "Invoice: {$invoice->invoice_number}\n" .
               "Amount Paid: {$symbol} " . number_format($payment->amount, 2) . "\n" .
               "Payment Method: " . ucwords(str_replace('_', ' ', $payment->payment_method)) . "\n" .
               "Date: " . $payment->payment_date->format('M d, Y H:i') . "\n" .
               "Reference: {$payment->payment_reference}\n\n" .
               "Remaining Balance: {$symbol} " . number_format($invoice->balance_amount, 2) . "\n\n" .
               "Thank you for your payment!";
    }
}
