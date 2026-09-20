<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\MpesaTransaction;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\ShaMember;
use App\Models\ShaServiceCode;
use App\Models\PatientInsurance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    public function __construct(protected PaymentGatewayService $gateway) {}

    /**
     * Initiate an M-Pesa STK push for a patient fee.
     *
     * Creates the invoice/payment/transaction chain (pending) and triggers the
     * STK push. The payment stays pending until the Daraja callback confirms it.
     *
     * @param array $data patient_id, fee_type, item_name, amount, phone,
     *                    source_type, source_id (nullable), invoice_id (optional -> pay existing invoice)
     */
    public function initiate(array $data): array
    {
        $patient = Patient::findOrFail($data['patient_id']);
        $feeType = $data['fee_type'] ?? 'invoice_payment';
        $amount = round((float) $data['amount'], 2);
        $phone = $this->normalizePhone($data['phone'] ?? $patient->phone ?? '');

        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Payment amount must be greater than zero.'];
        }

        if (!preg_match('/^254[17]\d{8}$/', $phone)) {
            return ['success' => false, 'message' => 'A valid M-Pesa phone number is required (Safaricom number).'];
        }

        $invoice = null;
        if (!empty($data['invoice_id'])) {
            $invoice = Invoice::findOrFail($data['invoice_id']);
            if ((int) $invoice->patient_id !== (int) $patient->id) {
                return ['success' => false, 'message' => 'Invoice does not belong to this patient.'];
            }
            if (round((float) $invoice->balance_amount, 2) < $amount) {
                return ['success' => false, 'message' => 'Payment amount exceeds the invoice balance.'];
            }
        }

        $itemName = $data['item_name'] ?? $this->feeLabel($feeType) . ' - ' . $patient->full_name;

        return DB::transaction(function () use ($patient, $invoice, $feeType, $itemName, $amount, $phone, $data) {
            if (!$invoice) {
                $invoice = $this->createInvoiceWithItem($patient, $feeType, $itemName, $amount);
            }

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'patient_id' => $patient->id,
                'amount' => $amount,
                'payment_method' => 'mpesa',
                'payment_date' => now(),
                'status' => MpesaTransaction::STATUS_PENDING,
            ]);

            $transaction = MpesaTransaction::create([
                'payment_id' => $payment->id,
                'patient_id' => $patient->id,
                'fee_type' => $feeType,
                'item_name' => $itemName,
                'amount' => $amount,
                'phone' => $phone,
                'status' => MpesaTransaction::STATUS_PENDING,
                'source_type' => $data['source_type'] ?? null,
                'source_id' => $data['source_id'] ?? null,
                'initiated_at' => now(),
                'transaction_data' => ['invoice_id' => $invoice->id],
            ]);

            $result = $this->gateway->processPayment($invoice, $amount, 'mpesa', ['phone' => $phone]);

            if (!$result['success'] || ($result['status'] ?? null) !== MpesaTransaction::STATUS_PENDING) {
                $payment->update(['status' => MpesaTransaction::STATUS_FAILED]);
                $transaction->update([
                    'status' => MpesaTransaction::STATUS_FAILED,
                    'result_desc' => $result['message'] ?? 'STK push failed',
                    'completed_at' => now(),
                ]);

                Log::warning('M-Pesa initiation failed', [
                    'payment_id' => $payment->id,
                    'message' => $result['message'] ?? 'STK push failed',
                ]);

                return ['success' => false, 'payment_id' => $payment->id, 'message' => $result['message'] ?? 'STK push failed'];
            }

            $checkoutRequestId = $result['transaction_id'];

            $payment->update(['payment_reference' => $checkoutRequestId]);
            $transaction->update(['checkout_request_id' => $checkoutRequestId]);

            Log::info('M-Pesa STK push initiated', [
                'payment_id' => $payment->id,
                'checkout_request_id' => $checkoutRequestId,
                'fee_type' => $feeType,
                'amount' => $amount,
                'phone' => $phone,
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'checkout_request_id' => $checkoutRequestId,
                'message' => 'M-Pesa payment initiated. Please check your phone and enter your PIN.',
            ];
        });
    }

    /**
     * Current pollable status for a M-Pesa payment.
     */
    public function status(int $paymentId): array
    {
        $payment = Payment::find($paymentId);
        if (!$payment) {
            return ['success' => false, 'status' => 'not_found'];
        }

        $transaction = MpesaTransaction::where('payment_id', $payment->id)->first();

        return [
            'success' => true,
            'payment_id' => $payment->id,
            'status' => $transaction?->status ?? $payment->status,
            'mpesa_receipt' => $transaction?->mpesa_receipt ?? null,
            'result_code' => $transaction?->result_code ?? null,
            'result_desc' => $transaction?->result_desc ?? null,
            'amount' => (float) $payment->amount,
            'fee_type' => $transaction?->fee_type ?? 'invoice_payment',
            'completed_at' => $transaction?->completed_at?->toDateTimeString(),
        ];
    }

    /**
     * Check whether a patient is SHA covered for the given fee type.
     */
    public function coverage(Patient $patient, string $feeType): array
    {
        $memberEligible = ShaMember::where('patient_id', $patient->id)
            ->where('eligibility_status', 'active')
            ->where('contribution_status', 'active')
            ->exists();

        $defaultCodes = config("mpesa.sha_service_codes.{$feeType}", []);
        $defaultCode = !empty($defaultCodes) ? $defaultCodes[0]['code'] : null;

        $mapped = ShaServiceCode::where('fee_type', $feeType)->where('is_active', true)->first();

        $serviceCode = $mapped ? $mapped->code : $defaultCode;
        $serviceCovered = $serviceCode !== null;

        $insuranceCovered = PatientInsurance::where('patient_id', $patient->id)
            ->where('is_active', true)
            ->whereHas('insuranceProvider', function ($q) {
                $q->where('name', 'like', '%SHA%')
                    ->orWhere('name', 'like', '%SHIF%')
                    ->orWhere('name', 'like', '%Social Health%')
                    ->orWhere('name', 'like', '%EHA%');
            })
            ->exists();

        $covered = ($memberEligible && $serviceCovered) || $insuranceCovered;

        return [
            'covered' => $covered,
            'member_eligible' => $memberEligible,
            'service_covered' => $serviceCovered,
            'service_code' => $serviceCode,
            'fee_label' => $this->feeLabel($feeType),
        ];
    }

    /**
     * Verify that a submitted payment_id is a completed M-Pesa payment for the
     * given patient/amount. Used as the server-side "blocked until paid" gate.
     */
    public function hasConfirmedPayment(int $patientId, int $paymentId, float $amount): bool
    {
        $payment = Payment::find($paymentId);

        if (!$payment || (int) $payment->patient_id !== (int) $patientId) {
            return false;
        }
        if ($payment->payment_method !== 'mpesa' || $payment->status !== MpesaTransaction::STATUS_COMPLETED) {
            return false;
        }
        if (round((float) $payment->amount, 2) < round($amount, 2)) {
            return false;
        }

        return true;
    }

    /**
     * Attach a paid transaction to the domain record that consumed it.
     */
    public function attachSource(int $paymentId, string $sourceType, int $sourceId): void
    {
        MpesaTransaction::where('payment_id', $paymentId)->update([
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);
    }

    protected function createInvoiceWithItem(Patient $patient, string $feeType, string $itemName, float $amount): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'patient_id' => $patient->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'subtotal' => $amount,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => $amount,
            'paid_amount' => 0,
            'balance_amount' => $amount,
            'status' => 'pending',
            'notes' => 'Generated for ' . $this->feeLabel($feeType),
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'item_type' => $feeType,
            'item_name' => $itemName,
            'description' => $this->feeLabel($feeType),
            'quantity' => 1,
            'unit_price' => $amount,
            'total_price' => $amount,
        ]);

        return $invoice;
    }

    public function normalizePhone(?string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        } elseif (strlen($phone) == 9) {
            $phone = '254' . $phone;
        }

        return $phone;
    }

    public function feeLabel(string $feeType): string
    {
        return self::feeLabelStatic($feeType);
    }

    public static function feeLabelStatic(string $feeType): string
    {
        return config("mpesa.fee_types.{$feeType}", ucwords(str_replace('_', ' ', $feeType)));
    }
}