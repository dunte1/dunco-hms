<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        @php
            $hospitalLogo = \App\Models\SystemSetting::get('hospital_logo', '');
        @endphp
        @if($hospitalLogo && file_exists(storage_path('app/public/' . $hospitalLogo)))
            @php
                $logoPath = storage_path('app/public/' . $hospitalLogo);
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoMime = mime_content_type($logoPath);
            @endphp
            <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Logo" style="max-height: 40px; margin-bottom: 10px;">
        @endif
        <h1 style="color: white; margin: 0;">{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p style="color: rgba(255,255,255,0.8); margin: 5px 0 0 0;">Payment Confirmed</p>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $payment->invoice->patient->first_name ?? 'Valued Customer' }},</p>
        
        <p>Thank you for your payment. Your transaction has been processed successfully:</p>

        @php
            $mpesaTxn = \App\Models\MpesaTransaction::where('payment_id', $payment->id)->first();
            $currency = \App\Models\SystemSetting::get('currency_symbol', 'KSh ');
        @endphp
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #06b6d4;">
            <h3 style="margin-top: 0; color: #06b6d4;">Payment Details</h3>
            @if($mpesaTxn?->fee_type)
                <p><strong>Fee Type:</strong> {{ \App\Services\MpesaService::feeLabelStatic($mpesaTxn->fee_type) }}</p>
            @endif
            @if($mpesaTxn?->item_name)
                <p><strong>Item:</strong> {{ $mpesaTxn->item_name }}</p>
            @endif
            <p><strong>Amount Paid:</strong> {{ $currency }}{{ number_format($payment->amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
            @if($payment->payment_method === 'mpesa')
                <p><strong>M-Pesa Receipt:</strong> {{ $mpesaTxn?->mpesa_receipt ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $mpesaTxn?->phone ?? 'N/A' }}</p>
                <p><strong>Payment Reference:</strong> {{ $payment->payment_reference ?? 'N/A' }}</p>
                <p><strong>Transaction Status:</strong> {{ ucfirst($mpesaTxn?->status ?? 'completed') }}</p>
            @endif
            <p><strong>Invoice Number:</strong> {{ $payment->invoice->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $payment->created_at->format('M d, Y h:i A') }}</p>
            @if($payment->invoice->balance_amount > 0)
            <p><strong>Remaining Balance:</strong> {{ $currency }}{{ number_format($payment->invoice->balance_amount, 2) }}</p>
            @endif
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('hms.billing.invoices.show', $payment->invoice_id) }}" style="background: #06b6d4; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Invoice</a>
        </div>
        
        <p style="font-size: 12px; color: #888;">Keep this receipt for your records. This receipt was issued automatically for your {{ $payment->payment_method === 'mpesa' ? 'M-Pesa' : 'payment' }} transaction.</p>
        
        <p>Best regards,<br>
        <strong>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p><strong>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</strong></p>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>{{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>&copy; {{ date('Y') }} {{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}. All rights reserved.</p>
    </div>
</body>
</html>

