<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #10b981; padding-bottom: 20px; }
        .header h1 { color: #10b981; font-size: 32px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 14px; }
        .invoice-info { display: table; width: 100%; margin-bottom: 30px; }
        .invoice-info > div { display: table-cell; width: 50%; vertical-align: top; }
        .invoice-info h3 { color: #10b981; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; }
        .invoice-info p { margin: 5px 0; }
        .invoice-number { background: #10b981; color: white; padding: 10px 15px; display: inline-block; font-size: 16px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        th { background: #f3f4f6; padding: 12px; text-align: left; font-weight: bold; border-bottom: 2px solid #10b981; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .text-right { text-align: right; }
        .totals { margin-top: 30px; }
        .totals table { width: 300px; margin-left: auto; }
        .totals td { padding: 8px; }
        .totals .total-row { background: #10b981; color: white; font-weight: bold; font-size: 16px; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; color: #666; font-size: 11px; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-partial { background: #fef3c7; color: #92400e; }
        .status-pending { background: #fee2e2; color: #991b1b; }
        .notes { background: #f9fafb; padding: 15px; margin-top: 30px; border-left: 4px solid #10b981; }
        .notes h4 { color: #10b981; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'DuncoHMS') }}</h1>
            <p>Hospital Management System</p>
            <p>{{ \App\Models\SystemSetting::get('hospital_address', 'Address not configured') }} | Tel: {{ \App\Models\SystemSetting::get('hospital_phone', 'Phone not configured') }}</p>
        </div>

        <!-- Invoice Number -->
        <div class="invoice-number">INVOICE #{{ $invoice->invoice_number }}</div>
        
        <!-- Status Badge -->
        <span class="status status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div>
                <h3>Bill To:</h3>
                <p><strong>{{ $invoice->patient->full_name }}</strong></p>
                <p>{{ $invoice->patient->email }}</p>
                <p>{{ $invoice->patient->phone }}</p>
                @if($invoice->patient->address)
                    <p>{{ $invoice->patient->address }}</p>
                @endif
            </div>
            <div style="text-align: right;">
                <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                @if($invoice->doctor)
                    <p><strong>Doctor:</strong> Dr. {{ $invoice->doctor->full_name }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->description }}</strong>
                        @if($item->item_type)
                            <br><small style="color: #666;">Type: {{ ucfirst($item->item_type) }}</small>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->tax_amount > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">${{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->discount_amount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-${{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td class="text-right">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                @if($invoice->paid_amount > 0)
                <tr>
                    <td>Paid:</td>
                    <td class="text-right">-${{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
                <tr style="background: #fef3c7; font-weight: bold;">
                    <td>Balance Due:</td>
                    <td class="text-right">${{ number_format($invoice->balance_amount, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
        <h3 style="color: #10b981; margin-top: 30px;">Payment History</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td>{{ $payment->payment_reference ?? 'N/A' }}</td>
                    <td class="text-right">${{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <h4>Notes:</h4>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>For any questions regarding this invoice, please contact us at {{ \App\Models\SystemSetting::get('hospital_email', 'billing@hospital.com') }} or call {{ \App\Models\SystemSetting::get('hospital_phone', '(123) 456-7890') }}</p>
            <p style="margin-top: 10px; font-size: 10px;">This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>
</body>
</html>


