<x-document title="Invoice" subtitle="{{ $invoice->invoice_number }}" documentNumber="{{ $invoice->invoice_number }}">

    <div class="section-title">Invoice Details</div>
    <table class="info-table">
        <tr><td>Invoice Number:</td><td><strong>{{ $invoice->invoice_number }}</strong></td></tr>
        <tr><td>Invoice Date:</td><td>{{ $invoice->invoice_date?->format('M d, Y') }}</td></tr>
        <tr><td>Due Date:</td><td>{{ $invoice->due_date?->format('M d, Y') }}</td></tr>
        <tr><td>Status:</td><td><strong style="color: {{ $invoice->status === 'paid' ? '#16a34a' : ($invoice->status === 'pending' ? '#d97706' : '#dc2626') }};">{{ strtoupper($invoice->status) }}</strong></td></tr>
    </table>

    <div class="section-title">Patient Information</div>
    <table class="info-table">
        <tr><td>Patient ID:</td><td>{{ $invoice->patient->patient_no ?? 'N/A' }}</td></tr>
        <tr><td>Patient Name:</td><td><strong>{{ $invoice->patient->full_name ?? 'N/A' }}</strong></td></tr>
        <tr><td>Phone:</td><td>{{ $invoice->patient->phone ?? 'N/A' }}</td></tr>
        <tr><td>Email:</td><td>{{ $invoice->patient->email ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Invoice Items</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Type</th>
                <th>Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->description ?? $item->service_name ?? 'N/A' }}</strong></td>
                <td>{{ ucfirst($item->item_type ?? 'Service') }}</td>
                <td>{{ $item->quantity ?? 1 }}</td>
                <td class="text-right">{{ $branding['currency_symbol'] }} {{ number_format($item->unit_price ?? $item->price ?? 0, 2) }}</td>
                <td class="text-right"><strong>{{ $branding['currency_symbol'] }} {{ number_format($item->total_price ?? $item->amount ?? 0, 2) }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">No items on this invoice.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px; float: right; width: 250px;">
        <table style="width: 100%;">
            <tr>
                <td style="padding: 4px 8px;"><strong>Subtotal</strong></td>
                <td class="text-right" style="padding: 4px 8px;">{{ $branding['currency_symbol'] }} {{ number_format($invoice->subtotal ?? $invoice->total_amount ?? 0, 2) }}</td>
            </tr>
            @if(($invoice->tax_amount ?? 0) > 0)
            <tr>
                <td style="padding: 4px 8px;">Tax ({{ $invoice->tax_rate ?? 0 }}%)</td>
                <td class="text-right" style="padding: 4px 8px;">{{ $branding['currency_symbol'] }} {{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if(($invoice->discount_amount ?? 0) > 0)
            <tr>
                <td style="padding: 4px 8px;">Discount</td>
                <td class="text-right" style="padding: 4px 8px;">-{{ $branding['currency_symbol'] }} {{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr style="border-top: 2px solid {{ $branding['primary_color'] }};">
                <td style="padding: 6px 8px; font-size: 13px;"><strong>Total Due</strong></td>
                <td class="text-right" style="padding: 6px 8px; font-size: 13px; font-weight: bold; color: {{ $branding['primary_color'] }};">{{ $branding['currency_symbol'] }} {{ number_format($invoice->balance_amount ?? $invoice->total_amount ?? 0, 2) }}</td>
            </tr>
            @if(($invoice->paid_amount ?? 0) > 0)
            <tr>
                <td style="padding: 4px 8px;">Paid</td>
                <td class="text-right" style="padding: 4px 8px; color: #16a34a;">{{ $branding['currency_symbol'] }} {{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>
    <div style="clear: both;"></div>

</x-document>
