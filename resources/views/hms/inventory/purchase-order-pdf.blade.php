<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 20px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 16px; font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; }
        .po-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .po-info .left, .po-info .right { width: 48%; }
        .po-info .box { padding: 12px; background: #f9fafb; border-radius: 5px; border-left: 4px solid #10b981; margin-bottom: 10px; }
        .po-info p { margin: 4px 0; font-size: 11px; }
        .po-info .label { font-weight: bold; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .totals { float: right; width: 300px; margin-top: 10px; }
        .totals table { width: 100%; }
        .totals td { border: none; padding: 4px 8px; font-size: 11px; }
        .totals tr.total-row td { border-top: 2px solid #10b981; font-weight: bold; font-size: 13px; color: #10b981; }
        .notes-section { clear: both; margin-top: 30px; padding: 12px; background: #f9fafb; border-radius: 5px; }
        .notes-section h4 { color: #10b981; margin: 0 0 8px 0; font-size: 12px; }
        .notes-section p { margin: 4px 0; font-size: 11px; }
        .signature { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature div { width: 45%; }
        .signature p { font-size: 11px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .no-print { margin: 20px 0; text-align: center; }
        .no-print button { background: #10b981; color: #fff; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        @media print {
            @page { size: A4; margin: 15mm; }
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $themeSettings = \App\Models\[ "primary_color" => \App\Models\SystemSetting::get("primary_color", "#10b981"), "hospital_logo" => \App\Models\SystemSetting::get("hospital_logo", ""), "hospital_name" => \App\Models\SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")), "hospital_address" => \App\Models\SystemSetting::get("hospital_address", ""), "hospital_phone" => \AppModels\SystemSetting::get("hospital_phone", ""), "hospital_email" => \App\Models\SystemSetting::get("hospital_email", "") ];
    @endphp
    <div class="header">
        @if(!empty($themeSettings['hospital_logo']))
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <div class="title">PURCHASE ORDER</div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <div class="po-info">
        <div class="left">
            <div class="box">
                <p><span class="label">PO Number:</span> {{ $purchaseOrder->po_number }}</p>
                <p><span class="label">Order Date:</span> {{ $purchaseOrder->order_date->format('M d, Y') }}</p>
                <p><span class="label">Expected Delivery:</span> {{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('M d, Y') : 'N/A' }}</p>
                <p><span class="label">Status:</span> {{ ucfirst($purchaseOrder->status) }}</p>
            </div>
        </div>
        <div class="right">
            <div class="box">
                <p class="label">Supplier Information</p>
                <p><strong>{{ $purchaseOrder->supplier->company_name ?? $purchaseOrder->supplier->name ?? 'N/A' }}</strong></p>
                <p>Contact: {{ $purchaseOrder->supplier->contact_person ?? 'N/A' }}</p>
                <p>Email: {{ $purchaseOrder->supplier->email ?? 'N/A' }}</p>
                <p>Phone: {{ $purchaseOrder->supplier->phone ?? 'N/A' }}</p>
                <p>Address: {{ $purchaseOrder->supplier->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:25%">Item Name</th>
                <th style="width:12%">Code</th>
                <th style="width:10%">Qty</th>
                <th style="width:12%" class="text-right">Unit Price</th>
                <th style="width:10%" class="text-right">Tax %</th>
                <th style="width:12%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrder->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item_name ?? $item->medicine->name ?? 'N/A' }}</td>
                    <td>{{ $item->item_code ?? '-' }}</td>
                    <td>{{ $item->quantity_ordered }}</td>
                    <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $item->tax_rate ?? 0 }}%</td>
                    <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($item->line_total ?? ($item->quantity_ordered * $item->unit_price), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($purchaseOrder->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($purchaseOrder->tax_amount, 2) }}</td>
            </tr>
            @if($purchaseOrder->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right">-{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($purchaseOrder->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($purchaseOrder->shipping_cost > 0)
            <tr>
                <td>Shipping:</td>
                <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($purchaseOrder->shipping_cost, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($purchaseOrder->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="notes-section" style="clear: both;">
        @if($purchaseOrder->notes)
            <h4>Delivery Instructions</h4>
            <p>{{ $purchaseOrder->notes }}</p>
        @endif
        @if($purchaseOrder->terms_and_conditions)
            <h4>Payment Terms</h4>
            <p>{{ $purchaseOrder->terms_and_conditions }}</p>
        @endif
    </div>

    <div class="signature">
        <div>
            <p><strong>Authorized By:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Name & Signature / Date</p>
        </div>
        <div>
            <p><strong>Supplier Acknowledgment:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Name & Signature / Date</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>

