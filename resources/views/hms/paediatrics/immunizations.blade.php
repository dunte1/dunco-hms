<h1>Immunization Schedules - Due List</h1>
<table>
    <thead>
        <tr><th>Patient</th><th>Vaccine</th><th>Dose</th><th>Due Date</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($schedules as $schedule)
        <tr>
            <td>{{ $schedule->patient->full_name }}</td>
            <td>{{ $schedule->vaccine->name }}</td>
            <td>{{ $schedule->dose_number }}</td>
            <td>{{ $schedule->due_date->format('Y-m-d') }}</td>
            <td>{{ $schedule->status }}</td>
        </tr>
        @empty
        <tr><td colspan="5">No immunizations due.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $schedules->links() }}
