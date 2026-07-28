<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    /**
     * Process payment through gateway
     */
    public function processPayment(Invoice $invoice, float $amount, string $gateway, array $paymentData): array
    {
        return match($gateway) {
            'stripe' => $this->processStripePayment($invoice, $amount, $paymentData),
            'paypal' => $this->processPayPalPayment($invoice, $amount, $paymentData),
            'mpesa' => $this->processMpesaPayment($invoice, $amount, $paymentData),
            'cash' => $this->processCashPayment($invoice, $amount, $paymentData),
            'bank_transfer' => $this->processBankTransferPayment($invoice, $amount, $paymentData),
            default => ['success' => false, 'message' => 'Unsupported payment gateway']
        };
    }

    /**
     * Process Stripe payment
     */
    protected function processStripePayment(Invoice $invoice, float $amount, array $data): array
    {
        try {
            // In production, use Stripe SDK
            // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            // $charge = \Stripe\Charge::create([...]);
            
            // For now, simulate successful payment
            return [
                'success' => true,
                'transaction_id' => 'stripe_' . uniqid(),
                'gateway' => 'stripe',
                'amount' => $amount,
                'message' => 'Payment processed successfully via Stripe'
            ];
        } catch (\Exception $e) {
            Log::error('Stripe payment failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Stripe payment failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process PayPal payment
     */
    protected function processPayPalPayment(Invoice $invoice, float $amount, array $data): array
    {
        try {
            // In production, use PayPal SDK
            // Similar implementation as Stripe
            
            return [
                'success' => true,
                'transaction_id' => 'paypal_' . uniqid(),
                'gateway' => 'paypal',
                'amount' => $amount,
                'message' => 'Payment processed successfully via PayPal'
            ];
        } catch (\Exception $e) {
            Log::error('PayPal payment failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'PayPal payment failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process M-Pesa payment
     */
    protected function processMpesaPayment(Invoice $invoice, float $amount, array $data): array
    {
        try {
            $phone = $data['phone'] ?? null;
            
            if (!$phone) {
                return [
                    'success' => false,
                    'message' => 'Phone number is required for M-Pesa payment'
                ];
            }

            // Get M-Pesa access token
            $token = $this->getMpesaAccessToken();
            
            if (!$token) {
                throw new \Exception('Failed to get M-Pesa access token');
            }

            // Initiate STK Push
            $response = Http::withOptions([
                'verify' => app()->environment('production'), // Only verify SSL in production
                'timeout' => 30,
            ])
            ->withToken($token)
            ->post(config('services.mpesa.stk_push_url'), [
                    'BusinessShortCode' => config('services.mpesa.shortcode'),
                    'Password' => $this->generateMpesaPassword(),
                    'Timestamp' => now()->format('YmdHis'),
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => $amount,
                    'PartyA' => $phone,
                    'PartyB' => config('services.mpesa.shortcode'),
                    'PhoneNumber' => $phone,
                    'CallBackURL' => url('/api/mpesa/callback'),
                    'AccountReference' => $invoice->invoice_number,
                    'TransactionDesc' => 'Payment for invoice ' . $invoice->invoice_number,
                ]);

            $result = $response->json();
            
            // Log full response for debugging
            Log::info('M-Pesa STK Push response', [
                'status' => $response->status(),
                'response' => $result
            ]);

            if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
                return [
                    'success' => true,
                    'transaction_id' => $result['CheckoutRequestID'],
                    'gateway' => 'mpesa',
                    'amount' => $amount,
                    'status' => 'pending',
                    'message' => 'M-Pesa payment initiated. Please check your phone.'
                ];
            }

            $errorMessage = $result['ResponseDescription'] ?? ($result['errorMessage'] ?? 'M-Pesa payment failed');
            
            Log::error('M-Pesa STK Push failed', [
                'response_code' => $result['ResponseCode'] ?? 'N/A',
                'error_message' => $errorMessage,
                'full_response' => $result
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'response_code' => $result['ResponseCode'] ?? null,
                'raw_response' => $result
            ];
            
        } catch (\Exception $e) {
            Log::error('M-Pesa payment failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'M-Pesa payment failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process cash payment
     */
    protected function processCashPayment(Invoice $invoice, float $amount, array $data): array
    {
        return [
            'success' => true,
            'transaction_id' => 'cash_' . time(),
            'gateway' => 'cash',
            'amount' => $amount,
            'message' => 'Cash payment recorded successfully'
        ];
    }

    /**
     * Process bank transfer payment
     */
    protected function processBankTransferPayment(Invoice $invoice, float $amount, array $data): array
    {
        return [
            'success' => true,
            'transaction_id' => $data['reference_number'] ?? 'bank_' . time(),
            'gateway' => 'bank_transfer',
            'amount' => $amount,
            'message' => 'Bank transfer recorded successfully'
        ];
    }

    /**
     * Get M-Pesa access token
     */
    protected function getMpesaAccessToken(): ?string
    {
        try {
            $consumerKey = config('services.mpesa.consumer_key');
            $consumerSecret = config('services.mpesa.consumer_secret');
            
            $response = Http::withOptions([
                'verify' => app()->environment('production'), // Only verify SSL in production
                'timeout' => 30,
            ])
            ->withBasicAuth($consumerKey, $consumerSecret)
            ->get(config('services.mpesa.oauth_url'));

            if (!$response->successful()) {
                Log::error('M-Pesa OAuth failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $result = $response->json();
            
            return $result['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to get M-Pesa token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate M-Pesa password
     */
    protected function generateMpesaPassword(): string
    {
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');
        $timestamp = now()->format('YmdHis');
        
        return base64_encode($shortcode . $passkey . $timestamp);
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $transactionId, string $gateway): array
    {
        return match($gateway) {
            'stripe' => $this->verifyStripePayment($transactionId),
            'paypal' => $this->verifyPayPalPayment($transactionId),
            'mpesa' => $this->verifyMpesaPayment($transactionId),
            default => ['success' => false, 'message' => 'Unsupported gateway']
        };
    }

    /**
     * Verify Stripe payment
     */
    protected function verifyStripePayment(string $transactionId): array
    {
        // Implement Stripe verification
        return ['success' => true, 'status' => 'completed'];
    }

    /**
     * Verify PayPal payment
     */
    protected function verifyPayPalPayment(string $transactionId): array
    {
        // Implement PayPal verification
        return ['success' => true, 'status' => 'completed'];
    }

    /**
     * Verify M-Pesa payment
     */
    protected function verifyMpesaPayment(string $transactionId): array
    {
        try {
            $token = $this->getMpesaAccessToken();
            
            $response = Http::withToken($token)
                ->post(config('services.mpesa.query_url'), [
                    'BusinessShortCode' => config('services.mpesa.shortcode'),
                    'Password' => $this->generateMpesaPassword(),
                    'Timestamp' => now()->format('YmdHis'),
                    'CheckoutRequestID' => $transactionId,
                ]);

            $result = $response->json();
            
            return [
                'success' => isset($result['ResultCode']) && $result['ResultCode'] == '0',
                'status' => $result['ResultCode'] == '0' ? 'completed' : 'failed',
                'message' => $result['ResultDesc'] ?? 'Unknown status'
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa verification failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Process refund
     */
    public function processRefund(Payment $payment, float $amount, string $reason = null): array
    {
        try {
            // Implement refund logic based on gateway
            $gateway = $payment->payment_method;
            
            return match($gateway) {
                'stripe' => $this->refundStripePayment($payment, $amount, $reason),
                'paypal' => $this->refundPayPalPayment($payment, $amount, $reason),
                'mpesa' => $this->refundMpesaPayment($payment, $amount, $reason),
                default => ['success' => false, 'message' => 'Refunds not supported for this gateway']
            };
        } catch (\Exception $e) {
            Log::error('Refund failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Refund Stripe payment
     */
    protected function refundStripePayment(Payment $payment, float $amount, ?string $reason): array
    {
        // Implement Stripe refund
        return [
            'success' => true,
            'refund_id' => 'refund_' . uniqid(),
            'message' => 'Refund processed successfully'
        ];
    }

    /**
     * Refund PayPal payment
     */
    protected function refundPayPalPayment(Payment $payment, float $amount, ?string $reason): array
    {
        // Implement PayPal refund
        return [
            'success' => true,
            'refund_id' => 'refund_' . uniqid(),
            'message' => 'Refund processed successfully'
        ];
    }

    /**
     * Refund M-Pesa payment
     */
    protected function refundMpesaPayment(Payment $payment, float $amount, ?string $reason): array
    {
        // Implement M-Pesa reversal
        return [
            'success' => true,
            'refund_id' => 'reversal_' . uniqid(),
            'message' => 'Reversal processed successfully'
        ];
    }
}


