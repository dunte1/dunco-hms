<x-document title="Prescription" subtitle="Medical Prescription" documentNumber="RX-{{ $prescription->id }}">

    <div class="summary">
        <div class="summary-box">
            <div class="amount">RX-{{ $prescription->id }}</div>
            <div class="label">Prescription ID</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $prescription->prescription_date?->format('M d, Y') ?? now()->format('M d, Y') }}</div>
            <div class="label">Date</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ ucfirst($prescription->status ?? 'Active') }}</div>
            <div class="label">Status</div>
        </div>
    </div>

    <div class="section-title">Patient & Doctor</div>
    <table class="info-table">
        <tr><td>Patient ID:</td><td>{{ $prescription->patient->patient_no ?? 'N/A' }}</td></tr>
        <tr><td>Patient Name:</td><td><strong>{{ $prescription->patient->full_name ?? 'N/A' }}</strong></td></tr>
        <tr><td>Age/Gender:</td>
            <td>
                @if($prescription->patient->dob)
                    {{ \Carbon\Carbon::parse($prescription->patient->dob)->age }} / {{ ucfirst($prescription->patient->gender ?? 'N/A') }}
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr><td>Doctor:</td><td><strong>Dr. {{ $prescription->doctor->full_name ?? 'N/A' }}</strong></td></tr>
        <tr><td>Qualification:</td><td>{{ $prescription->doctor->qualification ?? 'MBChB' }}</td></tr>
    </table>

    <div class="section-title">Prescribed Medicines</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Medicine</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Duration</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescription->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->medicine->name ?? 'N/A' }}</strong></td>
                <td>{{ $item->dosage ?? $item->medicine->dosage ?? 'N/A' }}</td>
                <td>{{ $item->frequency ?? 'N/A' }}</td>
                <td>{{ $item->duration ?? 'N/A' }}</td>
                <td>{{ $item->quantity ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">No medicines prescribed.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($prescription->notes ?? null)
    <div class="section-title">Instructions</div>
    <p style="background: #f9faf9; padding: 10px; border-left: 3px solid {{ $branding['primary_color'] }}; font-size: 10px;">{{ $prescription->notes }}</p>
    @endif

</x-document>
