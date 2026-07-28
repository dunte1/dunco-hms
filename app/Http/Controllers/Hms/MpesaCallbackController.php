<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Notifications\PaymentReceived;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MpesaCallbackController extends Controller
{
    public function __construct(
        protected SmsService $smsService
    ) {}

    /**
     * Handle M-Pesa STK Push callback
     */
    public function handleCallback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa callback received', $data);

            // M-Pesa callback structure
            $body = $data['Body'] ?? [];
            $stkCallback = $body['stkCallback'] ?? [];
            
            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;
            $callbackMetadata = $stkCallback['CallbackMetadata'] ?? [];
            $items = $callbackMetadata['Item'] ?? [];

            // Extract transaction details
            $mpesaReceiptNumber = null;
            $transactionDate = null;
            $phoneNumber = null;
            $amount = null;

            foreach ($items as $item) {
                $name = $item['Name'] ?? '';
                $value = $item['Value'] ?? '';
                
                switch ($name) {
                    case 'Amount':
                        $amount = $value;
                        break;
                    case 'MpesaReceiptNumber':
                        $mpesaReceiptNumber = $value;
                        break;
                    case 'TransactionDate':
                        $transactionDate = $value;
                        break;
                    case 'PhoneNumber':
                        $phoneNumber = $value;
                        break;
                }
            }

            // ResultCode 0 means success
            if ($resultCode == 0 && $mpesaReceiptNumber) {
                // Find payment by checkout request ID
                $payment = Payment::where('payment_reference', $checkoutRequestId)
                    ->where('payment_method', 'mpesa')
                    ->first();

                if ($payment) {
                    // Check if payment was already pending (not yet applied to invoice)
                    $wasPending = $payment->status === 'pending';
                    
                    // Update payment with M-Pesa receipt number
                    $payment->update([
                        'payment_reference' => $mpesaReceiptNumber,
                        'status' => 'completed',
                        'transaction_data' => [
                            'mpesa_receipt' => $mpesaReceiptNumber,
                            'transaction_date' => $transactionDate,
                            'phone_number' => $phoneNumber,
                            'amount' => $amount,
                        ]
                    ]);

                    // If payment was pending, now update the invoice status
                    if ($wasPending) {
                        $invoice = $payment->invoice;
                        if ($invoice) {
                            // Update invoice payment status automatically
                            $invoice->paid_amount += $payment->amount;
                            $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
                            
                            // Update invoice status automatically
                            if ($invoice->balance_amount <= 0) {
                                $invoice->status = 'paid';
                            } elseif ($invoice->paid_amount > 0) {
                                $invoice->status = 'partial';
                            }
                            
                            $invoice->save();
                            
                            Log::info('Invoice status updated automatically after M-Pesa payment', [
                                'invoice_id' => $invoice->id,
                                'invoice_number' => $invoice->invoice_number,
                                'status' => $invoice->status,
                                'balance_amount' => $invoice->balance_amount
                            ]);
                        }
                    }

                    // Send receipt notifications
                    $this->sendReceiptNotifications($payment);

                    return response()->json([
                        'ResultCode' => 0,
                        'ResultDesc' => 'Accepted'
                    ]);
                }
            }

            Log::warning('M-Pesa callback failed or payment not found', [
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
                'checkout_request_id' => $checkoutRequestId
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Failed'
            ]);

        } catch (\Exception $e) {
            Log::error('M-Pesa callback error', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing callback'
            ], 500);
        }
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

        // Send SMS receipt
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

                $response = Http::withBasicAuth($twilioSid, $twilioToken)
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
     * Handle C2B Result URL
     * This endpoint receives payment results from M-Pesa C2B API
     */
    public function handleResult(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa C2B Result received', $data);

            // C2B Result structure
            $result = $data['Result'] ?? [];
            $resultCode = $result['ResultCode'] ?? null;
            $resultDesc = $result['ResultDesc'] ?? null;
            $transactionId = $result['TransactionID'] ?? null;

            // Process result if needed
            if ($resultCode == 0) {
                Log::info('M-Pesa C2B Result: Success', [
                    'transaction_id' => $transactionId,
                    'description' => $resultDesc
                ]);
            } else {
                Log::warning('M-Pesa C2B Result: Failed', [
                    'result_code' => $resultCode,
                    'description' => $resultDesc
                ]);
            }

            // M-Pesa expects a response
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted'
            ]);

        } catch (\Exception $e) {
            Log::error('M-Pesa C2B Result error', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing result'
            ], 500);
        }
    }

    /**
     * Handle C2B Confirmation URL
     * This endpoint receives payment confirmations from M-Pesa C2B API
     */
    public function handleConfirmation(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa C2B Confirmation received', $data);

            // C2B Confirmation structure
            $transactionType = $data['TransactionType'] ?? null;
            $transId = $data['TransID'] ?? null;
            $transTime = $data['TransTime'] ?? null;
            $transAmount = $data['TransAmount'] ?? null;
            $businessShortCode = $data['BusinessShortCode'] ?? null;
            $billRefNumber = $data['BillRefNumber'] ?? null;
            $invoiceNumber = $data['InvoiceNumber'] ?? null;
            $orgAccountBalance = $data['OrgAccountBalance'] ?? null;
            $thirdPartyTransId = $data['ThirdPartyTransID'] ?? null;
            $msisdn = $data['MSISDN'] ?? null;
            $firstName = $data['FirstName'] ?? null;
            $middleName = $data['MiddleName'] ?? null;
            $lastName = $data['LastName'] ?? null;

            // Process the confirmation
            if ($transactionType === 'Pay Bill' && $transId) {
                // Find or create payment record
                $payment = Payment::where('payment_reference', $transId)
                    ->where('payment_method', 'mpesa')
                    ->first();

                if (!$payment && $invoiceNumber) {
                    // Try to find invoice by reference number
                    $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();
                    
                    if ($invoice) {
                        // Create payment record
                        $payment = Payment::create([
                            'invoice_id' => $invoice->id,
                            'patient_id' => $invoice->patient_id,
                            'amount' => $transAmount,
                            'payment_method' => 'mpesa',
                            'payment_reference' => $transId,
                            'payment_date' => now(),
                            'status' => 'completed',
                            'transaction_data' => [
                                'transaction_type' => $transactionType,
                                'trans_time' => $transTime,
                                'msisdn' => $msisdn,
                                'first_name' => $firstName,
                                'middle_name' => $middleName,
                                'last_name' => $lastName,
                                'business_short_code' => $businessShortCode,
                                'bill_ref_number' => $billRefNumber,
                                'org_account_balance' => $orgAccountBalance,
                            ]
                        ]);

                        // Update invoice
                        $invoice->paid_amount += $transAmount;
                        $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
                        $invoice->status = $invoice->balance_amount <= 0 ? 'paid' : 'partial';
                        $invoice->save();

                        // Send receipt notifications
                        $this->sendReceiptNotifications($payment);

                        Log::info('M-Pesa C2B Payment processed', [
                            'payment_id' => $payment->id,
                            'transaction_id' => $transId,
                            'invoice_number' => $invoiceNumber
                        ]);
                    }
                } elseif ($payment && $payment->status === 'pending') {
                    // Update existing payment
                    $payment->update([
                        'status' => 'completed',
                        'transaction_data' => array_merge(
                            $payment->transaction_data ?? [],
                            [
                                'transaction_type' => $transactionType,
                                'trans_time' => $transTime,
                                'msisdn' => $msisdn,
                                'confirmed' => true,
                            ]
                        )
                    ]);

                    // Update invoice if needed
                    $invoice = $payment->invoice;
                    if ($invoice) {
                        $invoice->paid_amount += $payment->amount;
                        $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
                        $invoice->status = $invoice->balance_amount <= 0 ? 'paid' : 'partial';
                        $invoice->save();
                    }

                    // Send receipt notifications
                    $this->sendReceiptNotifications($payment);
                }
            }

            // M-Pesa expects a response
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted'
            ]);

        } catch (\Exception $e) {
            Log::error('M-Pesa C2B Confirmation error', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing confirmation'
            ], 500);
        }
    }

    /**
     * Handle C2B Validation URL
     * This endpoint validates incoming C2B payment requests
     */
    public function handleValidation(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa C2B Validation received', $data);

            // C2B Validation structure
            $transactionType = $data['TransactionType'] ?? null;
            $transId = $data['TransID'] ?? null;
            $transTime = $data['TransTime'] ?? null;
            $transAmount = $data['TransAmount'] ?? null;
            $businessShortCode = $data['BusinessShortCode'] ?? null;
            $billRefNumber = $data['BillRefNumber'] ?? null;
            $invoiceNumber = $data['InvoiceNumber'] ?? null;
            $orgAccountBalance = $data['OrgAccountBalance'] ?? null;
            $thirdPartyTransId = $data['ThirdPartyTransID'] ?? null;
            $msisdn = $data['MSISDN'] ?? null;
            $firstName = $data['FirstName'] ?? null;
            $middleName = $data['MiddleName'] ?? null;
            $lastName = $data['LastName'] ?? null;

            // Validate the transaction
            $validationResult = $this->validateTransaction([
                'transaction_type' => $transactionType,
                'amount' => $transAmount,
                'invoice_number' => $invoiceNumber,
                'bill_ref_number' => $billRefNumber,
                'msisdn' => $msisdn,
            ]);

            // M-Pesa expects a validation response
            return response()->json([
                'ResultCode' => $validationResult['code'],
                'ResultDesc' => $validationResult['description']
            ]);

        } catch (\Exception $e) {
            Log::error('M-Pesa C2B Validation error', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing validation'
            ], 500);
        }
    }

    /**
     * Validate C2B transaction
     */
    protected function validateTransaction(array $data): array
    {
        // Basic validation
        if (empty($data['amount']) || $data['amount'] <= 0) {
            return [
                'code' => 1,
                'description' => 'Invalid amount'
            ];
        }

        // Check if invoice exists (if invoice number provided)
        if (!empty($data['invoice_number'])) {
            $invoice = Invoice::where('invoice_number', $data['invoice_number'])->first();
            if (!$invoice) {
                return [
                    'code' => 1,
                    'description' => 'Invoice not found'
                ];
            }

            // Check if invoice is already paid
            if ($invoice->status === 'paid' && $invoice->balance_amount <= 0) {
                return [
                    'code' => 1,
                    'description' => 'Invoice already paid'
                ];
            }
        }

        // If all validations pass
        return [
            'code' => 0,
            'description' => 'Accepted'
        ];
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

