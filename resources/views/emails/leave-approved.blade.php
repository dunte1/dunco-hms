<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Leave Request Approved ✓</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $employee->first_name }},</p>
        
        <p>Your leave request has been approved:</p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #10b981;">
            <h3 style="margin-top: 0; color: #10b981;">Leave Details</h3>
            <p><strong>Leave Type:</strong> {{ $leaveRequest->leaveType->name ?? 'N/A' }}</p>
            <p><strong>Start Date:</strong> {{ $leaveRequest->start_date->format('M d, Y') }}</p>
            <p><strong>End Date:</strong> {{ $leaveRequest->end_date->format('M d, Y') }}</p>
            <p><strong>Days:</strong> {{ $leaveRequest->days }}</p>
            @if($leaveRequest->notes)
            <p><strong>Notes:</strong> {{ $leaveRequest->notes }}</p>
            @endif
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('hms.hr.leave-requests.index') }}" style="background: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Leave Requests</a>
        </div>
        
        <p>Enjoy your time off!</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

