<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Notifications\PaymentReceived;
use App\Services\PaymentGatewayService;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
