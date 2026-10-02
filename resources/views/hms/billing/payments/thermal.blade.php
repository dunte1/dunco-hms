{{-- Payment Thermal Receipt (80mm) --}}
@php
    $hospitalName = \App\Models\SystemSetting::get('hospital_name', 'Dunco HMS');
    $hospitalAddress = \App\Models\SystemSetting::get('hospital_address', '');
    $hospitalPhone = \App\Models\SystemSetting::get('hospital_phone', '');
    $primaryColor = \App\Models\SystemSetting::get('primary_color', '#000075');
    $currency = \App\Models\SystemSetting::get('currency_symbol', 'KES');
    $logo = \App\Models\SystemSetting::get('hospital_logo', '');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', monospace; font-size: 10px; color: #333; width: 80mm; margin: 0 auto; }
        .receipt-header { text-align: center; padding: 8px 5px; border-bottom: 1px dashed #333; }
        .receipt-header h2 { font-size: 12px; color: {{ $primaryColor }}; margin: 3px 0; }
        .receipt-header p { font-size: 9px; color: #666; }
        .receipt-body { padding: 8px 5px; }
        .receipt-row { display: flex; justify-content: space-between; padding: 2px 0; font-size: 10px; }
        .receipt-row .label { color: #666; }
        .receipt-row .value { font-weight: bold; }
        .receipt-divider { border-top: 1px dashed #333; margin: 6px 0; }
        .receipt-total { text-align: center; font-size: 14px; font-weight: bold; color: {{ $primaryColor }}; padding: 6px 0; }
        .receipt-footer { text-align: center; padding: 8px 5px; border-top: 1px dashed #333; font-size: 9px; color: #666; }
        .receipt-footer .powered { color: #999; margin-top: 4px; }
        @page { size: 80mm auto; margin: 3mm; }
        @media print { body { width: 80mm; } }
    </style>
</head>
<body>
    <div class="receipt-header">
        @if($logo)
            <img src="{{ $logo }}" style="height: 30px; margin: 3px auto;">
        @endif
        <h2>{{ $hospitalName }}</h2>
        <p>{{ $hospitalAddress }}</p>
        <p>Tel: {{ $hospitalPhone }}</p>
    </div>
    
    <div class="receipt-body">
        @isset($payment)
        <div class="receipt-row">
            <span class="label">Payment #</span>
            <span class="value">PAY-{{ $payment->id }}</span>
        </div>
        <div class="receipt-row">
            <span class="label">Date</span>
            <span class="value">{{ $payment->payment_date?->format('d/m/Y H:i') }}</span>
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-row">
            <span class="label">Patient</span>
            <span class="value">{{ $payment->patient->full_name ?? 'N/A' }}</span>
        </div>
        <div class="receipt-row">
            <span class="label">Phone</span>
            <span class="value">{{ $payment->patient->phone ?? 'N/A' }}</span>
        </div>
        <div class="receipt-row">
            <span class="label">Invoice</span>
            <span class="value">{{ $payment->invoice->invoice_number ?? 'N/A' }}</span>
        </div>
        <div class="receipt-row">
            <span class="label">Method</span>
            <span class="value">{{ ucwords(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}</span>
        </div>
        @if($payment->transaction_data ?? null)
        <div class="receipt-row">
            <span class="label">M-Pesa Ref</span>
            <span class="value">{{ $payment->transaction_data['MpesaReceiptNumber'] ?? 'N/A' }}</span>
        </div>
        @endif
        <div class="receipt-row">
            <span class="label">Status</span>
            <span class="value" style="color: #16a34a;">{{ strtoupper($payment->status ?? 'Completed') }}</span>
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-total">
            {{ $currency }} {{ number_format($payment->amount, 2) }}
        </div>
        <div class="receipt-divider"></div>
        @endif
    </div>
    
    <div class="receipt-footer">
        <p>Payment received successfully!</p>
        <p>{{ $hospitalName }}</p>
        <p class="powered">Powered by Dunco Web Solutions</p>
    </div>
</body>
</html>
