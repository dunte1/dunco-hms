<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Http\Request;

class MpesaPaymentsController extends Controller
{
    public function __construct(protected MpesaService $mpesa) {}

    public function initiate(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'fee_type' => ['required', 'string', 'max:50'],
            'item_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'phone' => ['nullable', 'string', 'max:20'],
            'source_type' => ['nullable', 'string', 'max:50'],
            'source_id' => ['nullable', 'integer'],
            'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
        ]);

        $result = $this->mpesa->initiate($data);

        return $result['success']
            ? response()->json($result)
            : response()->json($result, 422);
    }

    public function status(Request $request, int $payment)
    {
        return response()->json($this->mpesa->status($payment));
    }

    public function coverage(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'fee_type' => ['required', 'string', 'max:50'],
        ]);

        $patient = Patient::findOrFail($data['patient_id']);

        return response()->json($this->mpesa->coverage($patient, $data['fee_type']));
    }

    public function receipt(Request $request, Payment $payment)
    {
        $patient = $payment->patient;

        if (!$patient || !$patient->email) {
            return back()->with('error', 'This patient has no email address on file to send the receipt to.');
        }

        $patient->notify(new \App\Notifications\PaymentReceived($payment));

        return back()->with('success', 'Receipt sent successfully.');
    }
}