<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Appointment Confirmed</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $patient->first_name }},</p>
        
        <p>Your appointment has been confirmed with the following details:</p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #667eea;">
            <h3 style="margin-top: 0; color: #667eea;">Appointment Details</h3>
            <p><strong>Doctor:</strong> Dr. {{ $appointment->doctor->full_name ?? 'TBD' }}</p>
            <p><strong>Date:</strong> {{ $appointment->appointment_date->format('l, F j, Y') }}</p>
            <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
            <p><strong>Department:</strong> {{ $appointment->doctor->department->name ?? 'General' }}</p>
            <p><strong>Appointment ID:</strong> {{ $appointment->appointment_number ?? 'N/A' }}</p>
            @if($appointment->notes)
            <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
            @endif
        </div>
        
        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0;"><strong>📋 Important:</strong> Please arrive 10 minutes early for your appointment. Bring your insurance card and a valid ID.</p>
        </div>
        
        <p>If you need to reschedule or cancel this appointment, please contact us at least 24 hours in advance.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('hms.appointments.index') }}" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Appointments</a>
        </div>
        
        <p>We look forward to seeing you soon.</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

