<x-document title="Laboratory Report" subtitle="Test Results Report" documentNumber="{{ $labRequest->request_number ?? 'LAB-' . $labRequest->id }}">

    <div class="summary">
        <div class="summary-box">
            <div class="amount">{{ $labRequest->request_number ?? 'N/A' }}</div>
            <div class="label">Request Number</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ ucfirst($labRequest->status ?? 'N/A') }}</div>
            <div class="label">Status</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $labRequest->request_date?->format('M d, Y') ?? 'N/A' }}</div>
            <div class="label">Request Date</div>
        </div>
    </div>

    <div class="section-title">Patient Information</div>
    <table class="info-table">
        <tr><td>Patient ID:</td><td>{{ $labRequest->patient->patient_no ?? 'N/A' }}</td></tr>
        <tr><td>Patient Name:</td><td><strong>{{ $labRequest->patient->full_name ?? 'N/A' }}</strong></td></tr>
        <tr><td>Age/Gender:</td>
            <td>
                @if($labRequest->patient->dob)
                    {{ \Carbon\Carbon::parse($labRequest->patient->dob)->age }} / {{ ucfirst($labRequest->patient->gender ?? 'N/A') }}
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr><td>Requesting Doctor:</td><td>Dr. {{ $labRequest->doctor->full_name ?? 'N/A' }}</td></tr>
    </table>

    @if($labRequest->clinical_notes)
    <div class="section-title">Clinical Notes</div>
    <p style="background: #f9faf9; padding: 10px; border-left: 3px solid {{ $branding['primary_color'] }}; font-size: 10px;">{{ $labRequest->clinical_notes }}</p>
    @endif

    <div class="section-title">Test Results</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Test Name</th>
                <th>Result</th>
                <th>Reference Range</th>
                <th>Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($labRequest->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->labTest->test_name ?? 'N/A' }}</strong></td>
                <td>{{ $item->result_value ?? $item->result ?? 'Pending' }}</td>
                <td>{{ $item->reference_range ?? 'N/A' }}</td>
                <td>{{ $item->unit ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">No test results available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($labRequest->results_notes)
    <div class="section-title">Results Notes</div>
    <p style="background: #f0fdf4; padding: 10px; border-left: 3px solid #16a34a; font-size: 10px;">{{ $labRequest->results_notes }}</p>
    @endif

</x-document>
