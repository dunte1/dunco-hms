<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shift Roster</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; margin: 0; padding: 20px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 20px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 16px; font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; padding: 10px; background: #f0fdf4; border-radius: 5px; }
        .info-row p { margin: 3px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: center; font-size: 10px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        td:first-child { text-align: left; font-weight: bold; }
        .shift-morning { background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 3px; font-weight: bold; display: inline-block; }
        .shift-afternoon { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 3px; font-weight: bold; display: inline-block; }
        .shift-night { background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 3px; font-weight: bold; display: inline-block; }
        .shift-off { background: #f3f4f6; color: #9ca3af; padding: 2px 6px; border-radius: 3px; display: inline-block; }
        .legend { margin-top: 15px; display: flex; gap: 15px; justify-content: center; }
        .legend span { display: inline-flex; align-items: center; gap: 5px; font-size: 10px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .no-print { margin: 20px 0; text-align: center; }
        .no-print button { background: #10b981; color: #fff; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        @media print {
            @page { size: A4 landscape; margin: 12mm; }
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $themeSettings = [
            'primary_color' => \App\Models\SystemSetting::get('primary_color', '#10b981'),
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS')),
            'hospital_address' => \App\Models\SystemSetting::get('hospital_address', ''),
            'hospital_phone' => \App\Models\SystemSetting::get('hospital_phone', ''),
            'hospital_email' => \App\Models\SystemSetting::get('hospital_email', ''),
        ];
    @endphp
    <div class="header">
        @if(!empty($themeSettings['hospital_logo']))
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <div class="title">SHIFT ROSTER</div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <div class="info-row">
        <div>
            <p><strong>Department:</strong> {{ $department ?? 'All Departments' }}</p>
        </div>
        <div>
            <p><strong>Week Starting:</strong> {{ $weekStarting ?? now()->startOfWeek()->format('M d, Y') }}</p>
        </div>
        <div>
            <p><strong>Generated:</strong> {{ now()->format('M d, Y') }}</p>
        </div>
    </div>

    @php
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weekStart = $weekStarting ? \Carbon\Carbon::parse($weekStarting)->startOfWeek() : now()->startOfWeek();
    @endphp

    <table>
        <thead>
            <tr>
                <th style="text-align:left; width:18%;">Staff Name</th>
                <th style="width:12%;">Position</th>
                @foreach($days as $dayIndex => $day)
                    @php $date = $weekStart->copy()->addDays($dayIndex); @endphp
                    <th style="width:10%;">{{ $day }}<br><span style="font-weight:normal; font-size:9px;">{{ $date->format('M d') }}</span></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($shifts as $shift)
                @php
                    $employee = $shift->employee;
                @endphp
                <tr>
                    <td style="text-align:left;">{{ $employee->full_name ?? 'N/A' }}</td>
                    <td>{{ $employee->position ?? $employee->department->name ?? 'N/A' }}</td>
                    @foreach($days as $dayIndex => $day)
                        @php
                            $dayShift = $shift->{"day_" . strtolower($day)} ?? ($shift->shifts[$day] ?? null);
                        @endphp
                        <td>
                            @if($dayShift && strtolower($dayShift) !== 'off')
                                @if(strtolower($dayShift) === 'morning')
                                    <span class="shift-morning">Morning</span>
                                @elseif(strtolower($dayShift) === 'afternoon')
                                    <span class="shift-afternoon">Afternoon</span>
                                @elseif(strtolower($dayShift) === 'night')
                                    <span class="shift-night">Night</span>
                                @else
                                    <span class="shift-morning">{{ ucfirst($dayShift) }}</span>
                                @endif
                            @else
                                <span class="shift-off">Off</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No shift data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="legend">
        <span><span class="shift-morning">Morning</span> 06:00 - 14:00</span>
        <span><span class="shift-afternoon">Afternoon</span> 14:00 - 22:00</span>
        <span><span class="shift-night">Night</span> 22:00 - 06:00</span>
        <span><span class="shift-off">Off</span> Day Off</span>
    </div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>



