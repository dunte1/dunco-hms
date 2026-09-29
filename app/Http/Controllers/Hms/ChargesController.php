<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChargesController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'service_id' => 'nullable|exists:services,id',
            'description' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $charge = DB::transaction(function () use ($data) {
            $charge = Charge::create([
                ...$data,
                'total' => $data['quantity'] * $data['unit_price'],
                'tax_amount' => isset($data['tax_rate'])
                    ? ($data['quantity'] * $data['unit_price']) * ($data['tax_rate'] / 100)
                    : 0,
                'status' => 'charged',
                'charged_by' => auth()->id(),
                'charged_at' => now(),
            ]);

            // If linked to invoice, update invoice subtotal
            if (!empty($data['invoice_id'])) {
                $invoice = Invoice::find($data['invoice_id']);
                if ($invoice) {
                    $chargeTotal = $charge->total + $charge->tax_amount;
                    $invoice->subtotal += $charge->total;
                    $invoice->tax_amount += $charge->tax_amount;
                    $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;
                    $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
                    $invoice->save();
                }
            }

            return $charge;
        });

        AuditLog::log('user', auth()->id(), 'charge_created', 'Charge', $charge->id, null, $charge->toArray(), 'Charge created: ' . $charge->description);

        return back()->with('status', 'Charge created successfully');
    }

    public function reverse(Charge $charge): RedirectResponse
    {
        if ($charge->status !== 'charged') {
            return back()->withErrors(['error' => 'Only charged items can be reversed.']);
        }

        DB::transaction(function () use ($charge) {
            $charge->reverse();

            // If linked to invoice, reverse the amounts
            if ($charge->invoice_id) {
                $invoice = Invoice::find($charge->invoice_id);
                if ($invoice) {
                    $chargeTotal = $charge->total + $charge->tax_amount;
                    $invoice->subtotal -= $charge->total;
                    $invoice->tax_amount -= $charge->tax_amount;
                    $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;
                    $invoice->balance_amount = $invoice->total_amount - $invoice->paid_amount;
                    $invoice->save();
                }
            }
        });

        AuditLog::log('user', auth()->id(), 'charge_reversed', 'Charge', $charge->id, ['status' => 'charged'], ['status' => 'reversed'], 'Charge reversed: ' . $charge->description);

        return back()->with('status', 'Charge reversed successfully');
    }
}
