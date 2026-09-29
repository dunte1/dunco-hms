<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Waiver;
use App\Models\Invoice;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountWaiverController extends Controller
{
    public function storeDiscount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'discount_type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string',
        ]);

        $invoice = Invoice::find($data['invoice_id']);

        // Calculate amount
        $amount = $data['discount_type'] === 'percentage'
            ? $invoice->subtotal * ($data['value'] / 100)
            : $data['value'];

        $discount = DB::transaction(function () use ($data, $amount, $invoice) {
            $discount = Discount::create([
                ...$data,
                'amount' => $amount,
                'approved_by' => auth()->id(),
                'is_active' => true,
            ]);

            // Apply discount to invoice
            $invoice->discount_amount += $amount;
            $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;
            $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
            $invoice->save();

            return $discount;
        });

        AuditLog::log('user', auth()->id(), 'discount_created', 'Discount', $discount->id, null, $discount->toArray(), 'Discount applied: $' . number_format($amount));

        return back()->with('status', 'Discount applied successfully');
    }

    public function storeWaiver(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        $waiver = Waiver::create([
            ...$data,
            'requested_by' => auth()->id(),
            'status' => 'pending',
        ]);

        AuditLog::log('user', auth()->id(), 'waiver_requested', 'Waiver', $waiver->id, null, $waiver->toArray(), 'Waiver requested: $' . number_format($waiver->amount));

        return back()->with('status', 'Waiver request submitted for approval');
    }

    public function approveWaiver(Waiver $waiver): RedirectResponse
    {
        if ($waiver->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending waivers can be approved.']);
        }

        DB::transaction(function () use ($waiver) {
            $waiver->approve(auth()->id());
        });

        AuditLog::log('user', auth()->id(), 'waiver_approved', 'Waiver', $waiver->id, ['status' => 'pending'], ['status' => 'approved'], 'Waiver approved: $' . number_format($waiver->amount));

        return back()->with('status', 'Waiver approved successfully');
    }

    public function rejectWaiver(Waiver $waiver): RedirectResponse
    {
        if ($waiver->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending waivers can be rejected.']);
        }

        $waiver->reject(auth()->id());

        AuditLog::log('user', auth()->id(), 'waiver_rejected', 'Waiver', $waiver->id, ['status' => 'pending'], ['status' => 'rejected'], 'Waiver rejected: $' . number_format($waiver->amount));

        return back()->with('status', 'Waiver rejected');
    }
}
