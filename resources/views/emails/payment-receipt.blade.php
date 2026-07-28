<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Payment Confirmed</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $payment->invoice->patient->first_name ?? 'Valued Customer' }},</p>
        
        <p>Thank you for your payment. Your transaction has been processed successfully:</p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #06b6d4;">
            <h3 style="margin-top: 0; color: #06b6d4;">Payment Details</h3>
            <p><strong>Payment ID:</strong> {{ $payment->payment_reference ?? 'N/A' }}</p>
            <p><strong>Invoice Number:</strong> {{ $payment->invoice->invoice_number }}</p>
            <p><strong>Amount Paid:</strong> {{ number_format($payment->amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
            <p><strong>Date:</strong> {{ $payment->created_at->format('M d, Y h:i A') }}</p>
            @if($payment->invoice->balance_amount > 0)
            <p><strong>Remaining Balance:</strong> {{ number_format($payment->invoice->balance_amount, 2) }}</p>
            @endif
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('hms.billing.invoices.show', $payment->invoice_id) }}" style="background: #06b6d4; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Invoice</a>
        </div>
        
        <p>Thank you for your payment. Keep this receipt for your records.</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

