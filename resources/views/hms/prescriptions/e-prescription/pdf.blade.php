<!DOCTYPE html>
<html>
<head>
    <title>E-Prescription - {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .patient-info, .medicines { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .signature { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>E-PRESCRIPTION</h2>
        @if($template && $template->header_text)
            <p>{{ $template->header_text }}</p>
        @endif
    </div>

    <div class="patient-info">
        <p><strong>Patient:</strong> {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</p>
        <p><strong>Date:</strong> {{ $prescription->prescription_date->format('M d, Y') }}</p>
        <p><strong>Doctor:</strong> Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</p>
    </div>

    @if($prescription->diagnosis)
        <div>
            <p><strong>Diagnosis:</strong> {{ $prescription->diagnosis }}</p>
        </div>
    @endif

    <div class="medicines">
        <h3>Medicines</h3>
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Quantity</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->items as $item)
                    <tr>
                        <td>{{ $item->medicine->name }}</td>
                        <td>{{ $item->dosage }}</td>
                        <td>{{ $item->frequency }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->duration_days }} days</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($prescription->digital_signature)
        <div class="signature">
            <img src="{{ $prescription->digital_signature }}" alt="Signature" style="max-height: 80px;">
            <p>Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</p>
            <p>{{ $prescription->signed_at->format('M d, Y') }}</p>
        </div>
    @endif

    @if($template && $template->footer_text)
        <div style="margin-top: 50px; text-align: center; font-size: 12px;">
            <p>{{ $template->footer_text }}</p>
        </div>
    @endif
</body>
</html>

