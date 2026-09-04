<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt #{{ $payment->id }}</title>
    <style>
        @media print {
            @page {
                width: 80mm;
                margin: 0;
            }
            body {
                width: 80mm;
            }
            .no-print {
                display: none;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }
        
        .receipt {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        
        .hospital-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .hospital-info {
            font-size: 10px;
            margin: 2px 0;
        }
        
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }
        
        .info-section {
            margin: 10px 0;
            font-size: 11px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .info-label {
            font-weight: bold;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        .items-table {
            width: 100%;
            margin: 10px 0;
            font-size: 11px;
        }
        
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .items-table td {
            padding: 3px 0;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-section {
            margin: 10px 0;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 12px;
        }
        
        .total-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px dashed #000;
        }
        
        .payment-info {
            margin: 15px 0;
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }
        
        .barcode {
            text-align: center;
            margin: 10px 0;
            font-family: 'Libre Barcode 39', cursive;
            font-size: 32px;
        }
        
        .thank-you {
            font-weight: bold;
            margin: 10px 0;
        }
        
        .no-print {
            text-align: center;
            margin: 20px 0;
        }
        
        .print-btn {
            background: #059669;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 5px;
        }
        
        .close-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 5px;
        }
        
        .print-btn:hover {
            background: #047857;
        }
        
        .close-btn:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-btn">🖨️ Print Receipt</button>
        <button onclick="window.close()" class="close-btn">✖ Close</button>
    </div>
    
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="hospital-name">{{ strtoupper(\App\Models\SystemSetting::get('hospital_name', config('app.name'))) }}</div>
            <div class="hospital-info">Professional Healthcare Services</div>
            <div class="hospital-info">Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '+254 700 000 000') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', 'info@example.com') }}</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
        </div>
        
        <!-- Receipt Info -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Receipt No:</span>
                <span>#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span>{{ $payment->payment_date->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Cashier:</span>
                <span>{{ auth()->user()->name ?? 'System' }}</span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Patient Info -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Patient:</span>
                <span>{{ $payment->invoice->patient->first_name }} {{ $payment->invoice->patient->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Patient ID:</span>
                <span>{{ $payment->invoice->patient->patient_no }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Invoice:</span>
                <span>{{ $payment->invoice->invoice_number }}</span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Items -->
        @if($payment->invoice->items && $payment->invoice->items->count() > 0)
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->invoice->items as $item)
                <tr>
                    <td>{{ Str::limit($item->description, 20) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="divider"></div>
        @endif
        
        <!-- Payment Details -->
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">Invoice Total:</span>
                <span>KSh {{ number_format($payment->invoice->total_amount, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Previous Payments:</span>
                <span>KSh {{ number_format($payment->invoice->paid_amount - $payment->amount, 2) }}</span>
            </div>
            <div class="info-row" style="font-weight: bold; font-size: 13px; margin-top: 5px;">
                <span>Amount Paid:</span>
                <span>KSh {{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Balance Due:</span>
                <span>KSh {{ number_format($payment->invoice->balance_amount, 2) }}</span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Payment Method -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span>{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</span>
            </div>
            @if($payment->payment_reference)
            <div class="info-row">
                <span class="info-label">Reference:</span>
                <span>{{ $payment->payment_reference }}</span>
            </div>
            @endif
        </div>
        
        <!-- Barcode -->
        <div class="barcode">
            *{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}*
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">THANK YOU FOR YOUR PAYMENT!</div>
            <div style="margin: 5px 0;">Keep this receipt for your records</div>
            <div style="margin: 5px 0;">{{ now()->format('d/m/Y H:i:s') }}</div>
            <div style="margin-top: 10px; font-size: 9px;">
                This is a computer-generated receipt
            </div>
        </div>
    </div>
    
    <script>
        // Auto-print option (optional - uncomment to enable)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

