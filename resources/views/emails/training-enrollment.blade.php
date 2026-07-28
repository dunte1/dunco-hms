<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Enrollment</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Training Enrollment Confirmed</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $employee->first_name }},</p>
        
        <p>You have been enrolled in the following training program:</p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #8b5cf6;">
            <h3 style="margin-top: 0; color: #8b5cf6;">Training Details</h3>
            <p><strong>Program:</strong> {{ $trainingProgram->title }}</p>
            <p><strong>Category:</strong> {{ $trainingProgram->category ?? 'General' }}</p>
            <p><strong>Start Date:</strong> {{ $trainingProgram->start_date->format('M d, Y') }}</p>
            @if($trainingProgram->end_date)
            <p><strong>End Date:</strong> {{ $trainingProgram->end_date->format('M d, Y') }}</p>
            @endif
            <p><strong>Duration:</strong> {{ $trainingProgram->duration_hours }} hours</p>
            @if($trainingProgram->location)
            <p><strong>Location:</strong> {{ $trainingProgram->location }}</p>
            @endif
            @if($trainingProgram->instructor)
            <p><strong>Instructor:</strong> {{ $trainingProgram->instructor }}</p>
            @endif
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('hms.hr.training-programs.show', $trainingProgram->id) }}" style="background: #8b5cf6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Details</a>
        </div>
        
        <p>We look forward to your participation!</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

