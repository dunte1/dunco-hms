<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabCriticalAlert;
use App\Models\LabRequestItem;
use App\Models\LabResultVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LabVerificationController extends Controller
{
    public function verify(Request $request, LabRequestItem $item): RedirectResponse
    {
        if ($item->result_value === null) {
            return back()->withErrors(['item' => 'Cannot verify an item without results.']);
        }

        LabResultVerification::create([
            'lab_request_item_id' => $item->id,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
            'status' => 'verified',
            'notes' => $request->input('notes'),
        ]);

        $item->update(['status' => 'completed']);
        $this->maybeCreateCriticalAlert($item);

        return redirect()->route('hms.laboratory.requests.show', $item->lab_request_id)
            ->with('status', 'Result verified successfully');
    }

    private function maybeCreateCriticalAlert(LabRequestItem $item): void
    {
        $labRequest = $item->labRequest;
        $test = $item->labTest;
        $value = $item->result_value;
        if ($value === null || $value === '') {
            return;
        }

        $normalRange = (string) ($test->normal_range ?? '');
        $isCritical = false;
        $message = '';

        if (!is_numeric($value)) {
            if (stripos($test->test_name ?? '', 'critical') !== false || stripos((string) $value, 'critical') !== false) {
                $isCritical = true;
                $message = "Critical text result for {$test->test_name}: {$value}";
            }
        } else {
            $num = (float) $value;
            if (preg_match('/<\s*([0-9.]+)/', $normalRange, $m) && $num > (float) $m[1]) {
                $isCritical = true;
                $message = "Critical: {$test->test_name} = {$value} (threshold < {$m[1]})";
            } elseif (preg_match('/>\s*([0-9.]+)/', $normalRange, $m) && $num < (float) $m[1]) {
                $isCritical = true;
                $message = "Critical low: {$test->test_name} = {$value} (threshold > {$m[1]})";
            } elseif (preg_match('/([0-9.]+)\s*-\s*([0-9.]+)/', $normalRange, $m)) {
                $lo = (float) $m[1];
                $hi = (float) $m[2];
                if ($num < $lo || $num > $hi) {
                    $isCritical = true;
                    $message = "Out of range: {$test->test_name} = {$value} (normal {$normalRange})";
                }
            }
        }

        if (!$isCritical) {
            return;
        }

        LabCriticalAlert::create([
            'lab_request_item_id' => $item->id,
            'patient_id' => $labRequest?->patient_id,
            'alert_type' => 'critical_result',
            'result_value' => (string) $value,
            'reference_range' => $normalRange,
            'message' => $message,
            'is_acknowledged' => false,
        ]);

        \Illuminate\Support\Facades\Log::warning('LAB_CRITICAL_ALERT', [
            'lab_request_id' => $labRequest?->id,
            'item_id' => $item->id,
            'message' => $message,
        ]);

        // Notify ordering clinician by email when available
        try {
            $doctor = $labRequest?->doctor;
            if ($doctor && filled($doctor->email)) {
                \Illuminate\Support\Facades\Mail::raw(
                    "CRITICAL LAB RESULT\n\n"
                    . "Request: " . ($labRequest->request_number ?? $labRequest?->id) . "\n"
                    . "Patient ID: " . ($labRequest->patient_id ?? '-') . "\n"
                    . "Test: " . ($test->test_name ?? 'Unknown') . "\n"
                    . "Result: {$value}\n"
                    . "Reference: {$normalRange}\n"
                    . "Alert: {$message}\n\n"
                    . "Please review and contact the laboratory immediately.",
                    function ($mail) use ($doctor, $test) {
                        $mail->to($doctor->email)
                            ->subject('CRITICAL LAB RESULT — ' . ($test->test_name ?? 'Laboratory'));
                    }
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Critical lab email failed', [
                'item_id' => $item->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function approve(Request $request, LabRequestItem $item): RedirectResponse
    {
        $verification = LabResultVerification::where('lab_request_item_id', $item->id)
            ->where('status', 'verified')
            ->latest()
            ->first();

        if (!$verification) {
            return back()->withErrors(['item' => 'No verified result found for this item.']);
        }

        $verification->update([
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'status' => 'approved',
        ]);

        $item->update(['status' => 'verified']);

        return redirect()->route('hms.laboratory.requests.show', $item->lab_request_id)
            ->with('status', 'Result approved successfully');
    }

    public function reject(Request $request, LabRequestItem $item): RedirectResponse
    {
        $data = $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $verification = LabResultVerification::where('lab_request_item_id', $item->id)
            ->whereIn('status', ['pending', 'verified'])
            ->latest()
            ->first();

        if (!$verification) {
            return back()->withErrors(['item' => 'No pending or verified result found for this item.']);
        }

        $verification->update([
            'status' => 'rejected',
            'notes' => $data['notes'],
        ]);

        $item->update(['status' => 'pending']);

        return redirect()->route('hms.laboratory.requests.show', $item->lab_request_id)
            ->with('status', 'Result rejected');
    }
}
