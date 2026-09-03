<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InsuranceClaim;
use App\Models\PatientInsurance;
use App\Models\Payment;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_invoices' => Invoice::count(),
            'unpaid_invoices' => Invoice::whereNotIn('status', ['paid', 'cancelled'])->count(),
            'total_payments' => Payment::count(),
            'total_payment_amount' => Payment::sum('amount'),
            'outstanding' => Invoice::whereNotIn('status', ['paid', 'cancelled'])->sum('total_amount'),
            'total_claims' => InsuranceClaim::count(),
            'pending_claims' => InsuranceClaim::where('status', 'pending')->count(),
            'insured_patients' => PatientInsurance::where('is_active', true)->count(),
        ];

        $recentInvoices = Invoice::with('patient')->latest()->take(10)->get();
        $recentPayments = Payment::with('invoice.patient')->latest('payment_date')->take(10)->get();

        $copaySummary = $this->getCopaySummary();

        return view('hms.billing.index', compact('stats', 'recentInvoices', 'recentPayments', 'copaySummary'));
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

    protected function getCopaySummary(): array
    {
        $insurances = PatientInsurance::with('insuranceProvider')
            ->where('is_active', true)
            ->get()
            ->groupBy('insurance_provider_id');

        $summary = [];
        foreach ($insurances as $providerId => $policies) {
            $provider = $policies->first()->insuranceProvider;
            $avgCopayment = $policies->avg('copayment_amount');
            $providerCopayPercentage = $provider->copayment_percentage ?? null;
            $summary[] = [
                'provider_name' => $provider->name ?? 'Unknown',
                'policy_count' => $policies->count(),
                'avg_copayment_amount' => $avgCopayment,
                'copayment_percentage' => $providerCopayPercentage,
                'coverage_amount' => $policies->avg('coverage_amount'),
            ];
        }

        return $summary;
    }
}
