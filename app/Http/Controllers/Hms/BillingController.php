<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        return view('hms.billing.index');
    }

    public function receipts(): View
    {
        $receipts = Payment::with(['invoice.patient', 'invoice.doctor'])
            ->latest('payment_date')
            ->paginate(15);
            
        return view('hms.billing.receipts.index', compact('receipts'));
    }

    public function paymentReports(): View
    {
        $payments = Payment::with(['invoice.patient', 'invoice.doctor'])
            ->latest('payment_date')
            ->get();
            
        // Calculate summary statistics
        $totalPayments = $payments->sum('amount');
        $totalCount = $payments->count();
        $todayPayments = $payments->where('payment_date', today())->sum('amount');
        $thisMonthPayments = $payments->whereBetween('payment_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->sum('amount');
        
        return view('hms.billing.payment-reports.index', compact(
            'payments', 
            'totalPayments', 
            'totalCount', 
            'todayPayments', 
            'thisMonthPayments'
        ));
    }
}


