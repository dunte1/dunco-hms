<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Leave Report</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $request)
                <tr>
                    <td>{{ $request->employee->full_name }}</td>
                    <td>{{ $request->leaveType->name ?? $request->leave_type }}</td>
                    <td>{{ $request->start_date->format('M d, Y') }}</td>
                    <td>{{ $request->end_date->format('M d, Y') }}</td>
                    <td>{{ $request->total_days }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

