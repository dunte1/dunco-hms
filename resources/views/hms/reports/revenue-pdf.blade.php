<x-document title="Revenue Report" subtitle="Payment Collection Summary" documentNumber="FIN-{{ now()->format('Ymd-His') }}">

    @php
        $totalAmount = $payments->sum('amount');
    @endphp

    <div class="summary">
        <div class="summary-box">
            <div class="amount">{{ number_format($payments->count()) }}</div>
            <div class="label">Total Payments</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $branding['currency_symbol'] }} {{ number_format($totalAmount, 2) }}</div>
            <div class="label">Total Revenue</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ number_format($payments->count() > 0 ? $totalAmount / $payments->count() : 0, 2) }}</div>
            <div class="label">Avg. Payment</div>
        </div>
    </div>

    <div class="section-title">Payment Records</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Invoice #</th>
                <th>Method</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->payment_date?->format('M d, Y') }}</td>
                <td><strong>{{ $payment->invoice->patient->full_name ?? 'N/A' }}</strong></td>
                <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
                <td class="text-right"><strong>{{ $branding['currency_symbol'] }} {{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">No payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</x-document>
