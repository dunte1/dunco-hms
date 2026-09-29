<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        $payment = Payment::findOrFail($data['payment_id']);

        if ($data['amount'] > $payment->amount) {
            return back()->withErrors(['amount' => 'Refund amount cannot exceed payment amount.']);
        }

        $refund = Refund::create([
            ...$data,
            'invoice_id' => $payment->invoice_id,
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        AuditLog::log('user', auth()->id(), 'refund_requested', 'Refund', $refund->id, null, $refund->toArray(), 'Refund requested: $' . number_format($refund->amount));

        return back()->with('status', 'Refund request submitted for approval');
    }

    public function approve(Refund $refund): RedirectResponse
    {
        if ($refund->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending refunds can be approved.']);
        }

        DB::transaction(function () use ($refund) {
            $refund->approve(auth()->id());
            $refund->process();
        });

        AuditLog::log('user', auth()->id(), 'refund_approved', 'Refund', $refund->id, ['status' => 'pending'], ['status' => 'completed'], 'Refund approved and processed: $' . number_format($refund->amount));

        return back()->with('status', 'Refund approved and processed');
    }

    public function reject(Refund $refund): RedirectResponse
    {
        if ($refund->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending refunds can be rejected.']);
        }

        $refund->reject(auth()->id());

        AuditLog::log('user', auth()->id(), 'refund_rejected', 'Refund', $refund->id, ['status' => 'pending'], ['status' => 'rejected'], 'Refund rejected: $' . number_format($refund->amount));

        return back()->with('status', 'Refund rejected');
    }
}
