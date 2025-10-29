<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit & Loss Statement</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            padding: 0;
        }
        .header h2 {
            font-size: 18px;
            margin: 10px 0 5px 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 6px 10px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: bold;
            font-size: 13px;
        }
        .revenue-total {
            color: #059669;
        }
        .expense-total {
            color: #dc2626;
        }
        .net-profit {
            border-top: 3px solid #333;
            padding-top: 10px;
            margin-top: 20px;
        }
        .net-profit td {
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
        }
        .profit-positive {
            color: #059669;
        }
        .profit-negative {
            color: #dc2626;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .profit-margin {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DUNCOHMS Hospital</h1>
        <h2>Profit & Loss Statement</h2>
        <p>Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</p>
    </div>

    <!-- Revenue Section -->
    <div class="section">
        <div class="section-title">REVENUE</div>
        <table>
            <tbody>
                @if($revenue['patient_services'] > 0)
                <tr>
                    <td>Patient Services</td>
                    <td class="text-right">KSh {{ number_format($revenue['patient_services'], 2) }}</td>
                </tr>
                @endif
                @if($revenue['pharmacy_sales'] > 0)
                <tr>
                    <td>Pharmacy Sales</td>
                    <td class="text-right">KSh {{ number_format($revenue['pharmacy_sales'], 2) }}</td>
                </tr>
                @endif
                @if($revenue['lab_tests'] > 0)
                <tr>
                    <td>Laboratory Tests</td>
                    <td class="text-right">KSh {{ number_format($revenue['lab_tests'], 2) }}</td>
                </tr>
                @endif
                @if($revenue['radiology'] > 0)
                <tr>
                    <td>Radiology Services</td>
                    <td class="text-right">KSh {{ number_format($revenue['radiology'], 2) }}</td>
                </tr>
                @endif
                @if($revenue['consultation_fees'] > 0)
                <tr>
                    <td>Consultation Fees</td>
                    <td class="text-right">KSh {{ number_format($revenue['consultation_fees'], 2) }}</td>
                </tr>
                @endif
                @if($revenue['other'] > 0)
                <tr>
                    <td>Other Income</td>
                    <td class="text-right">KSh {{ number_format($revenue['other'], 2) }}</td>
                </tr>
                @endif
                <tr class="total-row revenue-total">
                    <td>TOTAL REVENUE</td>
                    <td class="text-right">KSh {{ number_format($totalRevenue, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Expenses Section -->
    <div class="section">
        <div class="section-title">EXPENSES</div>
        <table>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>{{ $expense->category->name ?? 'Uncategorized' }}</td>
                    <td class="text-right">KSh {{ number_format($expense->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: #999;">No expenses recorded</td>
                </tr>
                @endforelse
                <tr class="total-row expense-total">
                    <td>TOTAL EXPENSES</td>
                    <td class="text-right">KSh {{ number_format($totalExpenses, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Net Profit Section -->
    <div class="net-profit">
        <table>
            <tbody>
                <tr>
                    <td class="{{ $grossProfit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                        NET {{ $grossProfit < 0 ? 'LOSS' : 'PROFIT' }}
                    </td>
                    <td class="text-right {{ $grossProfit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                        KSh {{ number_format($grossProfit, 2) }}
                    </td>
                </tr>
                <tr class="profit-margin">
                    <td>Profit Margin</td>
                    <td class="text-right">{{ number_format($profitMargin, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>&copy; {{ date('Y') }} DUNCOHMS Hospital. All rights reserved.</p>
    </div>
</body>
</html>

