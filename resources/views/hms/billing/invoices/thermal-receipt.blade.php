<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Receipt #{{ $invoice->invoice_number }}</title>
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
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-partial {
            background: #fef3c7;
            color: #92400e;
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
            <div class="hospital-name">DUNCOHMS HOSPITAL</div>
            <div class="hospital-info">Professional Healthcare Services</div>
            <div class="hospital-info">Tel: +254 700 000 000 | Email: info@duncohms.com</div>
            <div class="receipt-title">{{ $invoice->status === 'paid' ? 'RECEIPT' : 'INVOICE' }}</div>
        </div>
        
        <!-- Invoice Info -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Invoice No:</span>
                <span>{{ $invoice->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span>{{ $invoice->invoice_date->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ strtoupper($invoice->status) }}
                </span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Patient Info -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Patient:</span>
                <span>{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Patient ID:</span>
                <span>{{ $invoice->patient->patient_no }}</span>
            </div>
            @if($invoice->patient->phone)
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span>{{ $invoice->patient->phone }}</span>
            </div>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ Str::limit($item->description, 20) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="divider"></div>
        
        <!-- Totals -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>KSh {{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            
            @if($invoice->tax_amount > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>KSh {{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            
            @if($invoice->discount_amount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>-KSh {{ number_format($invoice->discount_amount, 2) }}</span>
            </div>
            @endif
            
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>KSh {{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            
            <div class="total-row">
                <span>Paid:</span>
                <span>KSh {{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            
            <div class="total-row" style="font-weight: bold;">
                <span>Balance:</span>
                <span>KSh {{ number_format($invoice->balance_amount, 2) }}</span>
            </div>
        </div>
        
        <!-- Payment History -->
        @if($invoice->payments && $invoice->payments->count() > 0)
        <div class="divider"></div>
        <div class="info-section">
            <div style="font-weight: bold; margin-bottom: 5px;">Payment History:</div>
            @foreach($invoice->payments as $payment)
            <div class="info-row" style="font-size: 10px;">
                <span>{{ $payment->payment_date->format('d/m/Y') }}</span>
                <span>KSh {{ number_format($payment->amount, 2) }}</span>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- Barcode -->
        <div class="barcode">
            *{{ str_replace('-', '', $invoice->invoice_number) }}*
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">THANK YOU FOR YOUR VISIT!</div>
            <div style="margin: 5px 0;">Please keep this receipt for your records</div>
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

