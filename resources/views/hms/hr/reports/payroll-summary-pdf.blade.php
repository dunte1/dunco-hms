<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Summary</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Payroll Summary</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>
    
    <div class="summary">
        <p><strong>Total Gross:</strong> {{ number_format($summary['total_gross'], 2) }}</p>
        <p><strong>Total Net:</strong> {{ number_format($summary['total_net'], 2) }}</p>
        <p><strong>Total Deductions:</strong> {{ number_format($summary['total_deductions'], 2) }}</p>
        <p><strong>Count:</strong> {{ $summary['count'] }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Date</th>
                <th>Gross Salary</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->full_name }}</td>
                    <td>{{ $payroll->pay_date->format('M d, Y') }}</td>
                    <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                    <td>{{ number_format($payroll->deductions, 2) }}</td>
                    <td>{{ number_format($payroll->net_salary, 2) }}</td>
                    <td>{{ ucfirst($payroll->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

