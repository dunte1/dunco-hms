<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvancePaymentsController extends Controller
{
    public function index(): View
    {
        $advancePayments = AdvancePayment::with('patient')
            ->latest('payment_date')
            ->paginate(15);
            
        // Calculate statistics
        $totalDeposits = AdvancePayment::sum('amount');
        $totalUsed = AdvancePayment::sum('used_amount');
        $totalBalance = AdvancePayment::sum('balance_amount');
        $totalCount = AdvancePayment::count();
        
        return view('hms.billing.advance-payments.index', compact(
            'advancePayments', 
            'totalDeposits', 
            'totalUsed', 
            'totalBalance', 
            'totalCount'
        ));
    }

    public function deposits(): View
    {
        $deposits = AdvancePayment::with('patient')
            ->where('status', 'active')
            ->latest('payment_date')
            ->paginate(15);
            
        // Calculate statistics
        $totalDeposits = AdvancePayment::where('status', 'active')->sum('amount');
        $totalUsed = AdvancePayment::where('status', 'active')->sum('used_amount');
        $totalBalance = AdvancePayment::where('status', 'active')->sum('balance_amount');
        $totalCount = AdvancePayment::where('status', 'active')->count();
        
        return view('hms.billing.advance-payments.deposits', compact(
            'deposits', 
            'totalDeposits', 
            'totalUsed', 
            'totalBalance', 
            'totalCount'
        ));
    }

    public function refunds(): View
    {
        $refunds = AdvancePayment::with('patient')
            ->where('status', 'refunded')
            ->latest('payment_date')
            ->paginate(15);
            
        // Calculate statistics
        $totalRefunds = AdvancePayment::where('status', 'refunded')->sum('amount');
        $totalCount = AdvancePayment::where('status', 'refunded')->count();
        
        return view('hms.billing.advance-payments.refunds', compact(
            'refunds', 
            'totalRefunds', 
            'totalCount'
        ));
    }

    public function processRefund(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'advance_payment_id' => 'required|exists:advance_payments,id',
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_reason' => 'required|string',
            'refund_date' => 'required|date',
        ]);

        $advancePayment = AdvancePayment::findOrFail($data['advance_payment_id']);
        
        // Validate refund amount doesn't exceed balance
        if ($data['refund_amount'] > $advancePayment->balance_amount) {
            return back()->withErrors(['refund_amount' => 'Refund amount cannot exceed available balance.']);
        }

        // Update advance payment
        $advancePayment->used_amount += $data['refund_amount'];
        $advancePayment->balance_amount = $advancePayment->amount - $advancePayment->used_amount;
        
        if ($advancePayment->balance_amount <= 0) {
            $advancePayment->status = 'refunded';
        }
        
        $advancePayment->notes = ($advancePayment->notes ? $advancePayment->notes . "\n" : '') . 
                               "Refund: $" . number_format($data['refund_amount'], 2) . " - " . $data['refund_reason'];
        $advancePayment->save();

        return redirect()->route('hms.advance-payments.refunds')
            ->with('status', 'Refund processed successfully');
    }
}