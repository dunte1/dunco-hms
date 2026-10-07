<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Receipt - {{ $patient->patient_no }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 1rem;
            color: #111827;
        }
        .receipt {
            max-width: 420px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff;
            padding: 1.25rem 1.5rem;
            text-align: center;
        }
        .header h1 { margin: 0; font-size: 1.25rem; }
        .header p { margin: 0.25rem 0 0; font-size: 0.85rem; opacity: .9; }
        .body { padding: 1.25rem 1.5rem 1.5rem; }
        .mrn-box {
            background: #eff6ff;
            border: 2px dashed #3b82f6;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        .mrn-box .label { font-size: 0.75rem; text-transform: uppercase; color: #1e40af; letter-spacing: .05em; }
        .mrn-box .value { font-size: 1.75rem; font-weight: 800; font-family: 'Courier New', monospace; color: #1e3a8a; }
        .row { display: flex; justify-content: space-between; gap: 1rem; padding: 0.45rem 0; border-bottom: 1px solid #e5e7eb; font-size: 0.9rem; }
        .row:last-child { border-bottom: none; }
        .row .k { color: #6b7280; }
        .row .v { font-weight: 600; text-align: right; }
        .note {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #065f46;
        }
        .actions { padding: 0 1.5rem 1.5rem; display: flex; gap: 0.75rem; }
        .actions button, .actions a {
            flex: 1;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn-print { background: #1d4ed8; color: #fff; }
        .btn-back { background: #e5e7eb; color: #111827; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; max-width: 100%; }
            .actions { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1><i class="fa fa-hospital me-2"></i>{{ $hospital }}</h1>
            <p>{{ $address }}</p>
            @if($phone)<p>{{ $phone }}</p>@endif
        </div>
        <div class="body">
            <div class="mrn-box">
                <div class="label">Registration Number</div>
                <div class="value">{{ $patient->patient_no }}</div>
                <div style="font-size:0.75rem;margin-top:0.35rem;color:#1e40af;">Use this number at Triage, OPD, Pharmacy &amp; Lab</div>
            </div>

            <div class="row"><span class="k">Patient Name</span><span class="v">{{ $patient->first_name }} {{ $patient->last_name }}</span></div>
            <div class="row"><span class="k">Phone</span><span class="v">{{ $patient->phone ?? '—' }}</span></div>
            @if($patient->national_id)
                <div class="row"><span class="k">National ID</span><span class="v">{{ $patient->national_id }}</span></div>
            @endif
            @if($patient->dob)
                <div class="row"><span class="k">Date of Birth</span><span class="v">{{ \Carbon\Carbon::parse($patient->dob)->format('d M Y') }}</span></div>
            @endif
            <div class="row"><span class="k">Gender</span><span class="v">{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</span></div>
            <div class="row"><span class="k">Registered</span><span class="v">{{ optional($patient->created_at)->format('d M Y H:i') }}</span></div>
            @if($opd)
                <div class="row"><span class="k">OPD Visit</span><span class="v">#{{ $opd->id }} · {{ $opd->status }}</span></div>
            @endif
            @if($queue)
                <div class="row"><span class="k">Queue Token</span><span class="v">{{ $queue->token_number }} ({{ $queue->department }})</span></div>
            @endif

            <div class="note">
                <strong>Next steps:</strong> Present this receipt (or registration number) at Triage.
                Triage can search by <strong>registration number</strong>, <strong>full name</strong>, or <strong>phone/National ID</strong>.
            </div>
        </div>
        <div class="actions">
            <button type="button" class="btn-print" onclick="window.print()">
                <i class="fa fa-print me-1"></i> Print Receipt
            </button>
            <a class="btn-back" href="{{ route('hms.patients.show', $patient) }}">
                <i class="fa fa-user me-1"></i> Patient Profile
            </a>
        </div>
    </div>
</body>
</html>
