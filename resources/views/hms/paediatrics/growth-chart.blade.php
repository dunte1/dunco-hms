<h1>Growth Chart - {{ $patient->full_name }}</h1>
<table>
    <thead>
        <tr><th>Date</th><th>Weight (g)</th><th>Height (cm)</th><th>Head Circ. (cm)</th><th>BMI</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($measurements as $m)
        <tr>
            <td>{{ $m->recorded_date->format('Y-m-d') }}</td>
            <td>{{ $m->weight_grams }}</td>
            <td>{{ $m->height_cm }}</td>
            <td>{{ $m->head_circumference_cm }}</td>
            <td>{{ $m->bmi }}</td>
            <td>{{ $m->malnutrition_status }}</td>
        </tr>
        @empty
        <tr><td colspan="6">No measurements recorded.</td></tr>
        @endforelse
    </tbody>
</table>
