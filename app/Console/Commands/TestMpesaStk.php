<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\PaymentGatewayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestMpesaStk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpesa:test-stk 
                            {phone : Phone number (format: 254712345678)}
                            {amount : Amount to pay}
                            {--invoice= : Invoice ID (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test M-Pesa STK Push functionality';

    /**
     * Execute the console command.
     */
    public function handle(PaymentGatewayService $paymentGateway): int
    {
        $this->info('🧪 Testing M-Pesa STK Push...');
        $this->newLine();

        // Get parameters
        $phone = $this->argument('phone');
        $amount = (float) $this->argument('amount');
        $invoiceId = $this->option('invoice');

        // Validate phone number format
        $phone = $this->formatPhoneNumber($phone);
        if (!$phone) {
            $this->error('❌ Invalid phone number format. Use: 254712345678 or 0712345678');
            return Command::FAILURE;
        }

        $this->info("📱 Phone Number: {$phone}");
        $this->info("💰 Amount: KES " . number_format($amount, 2));
        $this->newLine();

        // Check M-Pesa configuration
        $this->info('🔍 Checking M-Pesa configuration...');
        $config = $this->checkMpesaConfig();
        if (!$config['valid']) {
            $this->error('❌ M-Pesa configuration incomplete!');
            $this->error($config['message']);
            return Command::FAILURE;
        }
        $this->info('✅ M-Pesa configuration OK');
        $this->info("   Shortcode: " . config('services.mpesa.shortcode'));
        $this->info("   STK URL: " . config('services.mpesa.stk_push_url'));
        $this->newLine();

        // Get or create test invoice
        $invoice = $this->getOrCreateTestInvoice($invoiceId, $amount);

        $this->info("📄 Invoice: {$invoice->invoice_number}");
        $this->info("   Invoice ID: {$invoice->id}");
        $this->newLine();

        // Test access token
        $this->info('🔐 Getting M-Pesa access token...');
        try {
            $token = $this->getMpesaAccessToken();
            if ($token) {
                $this->info('✅ Access token obtained successfully');
            } else {
                $this->error('❌ Failed to get access token');
                $this->error('Check your MPESA_CONSUMER_KEY and MPESA_CONSUMER_SECRET in .env');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('❌ Error getting access token: ' . $e->getMessage());
            return Command::FAILURE;
        }
        $this->newLine();

        // Initiate STK Push
        $this->info('📤 Initiating STK Push...');
        
        try {
            $result = $paymentGateway->processPayment(
                $invoice,
                $amount,
                'mpesa',
                ['phone' => $phone]
            );

            if ($result['success']) {
                $this->info('✅ STK Push initiated successfully!');
                $this->newLine();
                $this->info('📋 Transaction Details:');
                $this->info("   Transaction ID: " . ($result['transaction_id'] ?? 'N/A'));
                $this->info("   Status: " . ($result['status'] ?? 'pending'));
                $this->info("   Message: " . ($result['message'] ?? 'N/A'));
                $this->newLine();
                $this->warn('📱 Please check your phone to complete the payment!');
                $this->info('   You should receive an M-Pesa prompt on: ' . $phone);
                $this->newLine();
                $this->info('🔄 Waiting for callback...');
                $this->info('   Callback URL: ' . url('/api/mpesa/callback'));
                $this->newLine();
                $this->info('💡 To check callback logs:');
                $this->info('   tail -f storage/logs/laravel.log | grep -i mpesa');
                $this->newLine();
                
                return Command::SUCCESS;
            } else {
                $this->error('❌ STK Push failed!');
                $this->error('   Error: ' . ($result['message'] ?? 'Unknown error'));
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            Log::error('M-Pesa STK test failed', [
                'phone' => $phone,
                'amount' => $amount,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX
     */
    protected function formatPhoneNumber(string $phone): ?string
    {
        // Remove spaces, dashes, plus signs
        $phone = preg_replace('/[\s\-\+]/', '', $phone);
        
        // If starts with 0, replace with 254
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        
        // If doesn't start with 254, add it
        if (!str_starts_with($phone, '254')) {
            $phone = '254' . $phone;
        }
        
        // Validate: should be 12 digits (254 + 9 digits)
        if (strlen($phone) !== 12 || !ctype_digit($phone)) {
            return null;
        }
        
        return $phone;
    }

    /**
     * Check M-Pesa configuration
     */
    protected function checkMpesaConfig(): array
    {
        $required = [
            'MPESA_CONSUMER_KEY' => config('services.mpesa.consumer_key'),
            'MPESA_CONSUMER_SECRET' => config('services.mpesa.consumer_secret'),
            'MPESA_SHORTCODE' => config('services.mpesa.shortcode'),
            'MPESA_PASSKEY' => config('services.mpesa.passkey'),
        ];

        $missing = [];
        foreach ($required as $key => $value) {
            if (empty($value)) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            return [
                'valid' => false,
                'message' => 'Missing configuration: ' . implode(', ', $missing) . "\n   Please add these to your .env file"
            ];
        }

        return ['valid' => true];
    }

    /**
     * Get M-Pesa access token
     */
    protected function getMpesaAccessToken(): ?string
    {
        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');
        $oauthUrl = config('services.mpesa.oauth_url');

        try {
            $response = \Illuminate\Support\Facades\Http::withOptions([
                'verify' => app()->environment('production'), // Only verify SSL in production
                'timeout' => 30,
            ])
            ->withBasicAuth($consumerKey, $consumerSecret)
            ->get($oauthUrl);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            Log::error('M-Pesa OAuth failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa OAuth exception', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get or create test invoice
     */
    protected function getOrCreateTestInvoice(?string $invoiceId, float $amount)
    {
        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                return $invoice;
            }
            $this->warn("Invoice ID {$invoiceId} not found. Creating test invoice...");
        }

        // Find or create a test patient
        $patient = \App\Models\Patient::first();
        
        if (!$patient) {
            // Create a test patient if none exists
            $patient = \App\Models\Patient::create([
                'patient_number' => 'TEST-' . now()->format('YmdHis'),
                'first_name' => 'Test',
                'last_name' => 'Patient',
                'email' => 'test@example.com',
                'phone' => '254700000000',
                'gender' => 'Male',
                'date_of_birth' => now()->subYears(30),
                'status' => 'active',
            ]);
            
            $this->info("   Created test patient: {$patient->patient_number}");
        }

        // Create test invoice
        $invoice = Invoice::create([
            'invoice_number' => 'TEST-' . now()->format('YmdHis'),
            'patient_id' => $patient->id,
            'total_amount' => $amount,
            'paid_amount' => 0,
            'balance_amount' => $amount,
            'status' => 'pending',
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'currency' => 'KES',
            'currency_symbol' => 'KSh',
        ]);

        return $invoice;
    }
}

