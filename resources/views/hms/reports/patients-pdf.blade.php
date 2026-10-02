<x-document title="Patient Report" subtitle="Complete Patient Registry" documentNumber="RPT-{{ now()->format('Ymd-His') }}">

    <div class="summary">
        <div class="summary-box">
            <div class="amount">{{ $patients->count() }}</div>
            <div class="label">Total Patients</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $patients->where('gender', 'male')->count() }}</div>
            <div class="label">Male</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $patients->where('gender', 'female')->count() }}</div>
            <div class="label">Female</div>
        </div>
    </div>

    <div class="section-title">Patient Registry</div>
    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td>{{ $patient->patient_no }}</td>
                <td><strong>{{ $patient->full_name }}</strong></td>
                <td>{{ $patient->email ?? 'N/A' }}</td>
                <td>{{ $patient->phone ?? 'N/A' }}</td>
                <td>{{ $patient->dob ? date('M d, Y', strtotime($patient->dob)) : 'N/A' }}</td>
                <td>{{ ucfirst($patient->gender ?? 'N/A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">No patients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</x-document>
