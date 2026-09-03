@extends('admin.layouts.app')

@section('title', 'Billing Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_invoices'] }}</p>
                </div>
                <i class="fa fa-file-invoice text-3xl text-blue-500"></i>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $stats['unpaid_invoices'] }} unpaid</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_payment_amount'], 2) }}</p>
                </div>
                <i class="fa fa-money-bill text-3xl text-green-500"></i>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $stats['total_payments'] }} transactions</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Outstanding</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['outstanding'], 2) }}</p>
                </div>
                <i class="fa fa-exclamation-triangle text-3xl text-red-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Insured Patients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['insured_patients'] }}</p>
                </div>
                <i class="fa fa-shield-halved text-3xl text-teal-500"></i>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $stats['pending_claims'] }} pending claims</p>
        </div>
    </div>

    <!-- Insurance Copay Summary -->
    @if(count($copaySummary) > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fa fa-credit-card text-teal-600 mr-2"></i> Insurance Copay Summary
            </h3>
            <p class="text-sm text-gray-500 mt-1">Average copayment and coverage by insurance provider</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Policies</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Copay (KES)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider Copay %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Coverage (KES)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($copaySummary as $copay)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $copay['provider_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $copay['policy_count'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $copay['avg_copayment_amount'] !== null ? number_format($copay['avg_copayment_amount'], 2) : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $copay['copayment_percentage'] !== null ? number_format($copay['copayment_percentage'], 1) . '%' : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $copay['coverage_amount'] !== null ? number_format($copay['coverage_amount'], 2) : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p class="text-gray-500 text-sm"><i class="fa fa-info-circle mr-2"></i> No active insurance policies to display copay summary. Add policies to see copayment breakdown.</p>
    </div>
    @endif

    <!-- Recent Invoices & Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                <a href="{{ route('hms.billing.invoices.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentInvoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $invoice->invoice_number ?? '#' . $invoice->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->patient->first_name ?? '' }} {{ $invoice->patient->last_name ?? '' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No invoices</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                <a href="{{ route('hms.billing.payment-reports') }}" class="text-sm text-blue-600 hover:text-blue-700">Reports</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentPayments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-green-600">{{ $payment->receipt_number ?? $payment->reference ?? ('#' . $payment->id) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->invoice->patient->first_name ?? '' }} {{ $payment->invoice->patient->last_name ?? '' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No payments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
