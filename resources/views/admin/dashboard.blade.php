@extends('admin.layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded shadow"><div class="text-xs text-gray-500">Invoice Amount</div><div class="text-2xl font-semibold">${{ number_format($metrics['invoiceAmount'], 2) }}</div></div>
        <div class="bg-white p-4 rounded shadow"><div class="text-xs text-gray-500">Bill Amount</div><div class="text-2xl font-semibold">${{ number_format($metrics['billAmount'], 2) }}</div></div>
        <div class="bg-white p-4 rounded shadow"><div class="text-xs text-gray-500">Payment Amount</div><div class="text-2xl font-semibold">${{ number_format($metrics['paymentAmount'], 2) }}</div></div>
        <div class="bg-white p-4 rounded shadow"><div class="text-xs text-gray-500">Advance Payment Amount</div><div class="text-2xl font-semibold">${{ number_format($metrics['advanceAmount'], 2) }}</div></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        @foreach([
            'Available Beds' => $metrics['availableBeds'],
            'Doctors' => $metrics['doctors'],
            'Patients' => $metrics['patients'],
            'Nurses' => $metrics['nurses'],
            'Admins' => $metrics['admins'],
            'Accountants' => $metrics['accountants'],
            'Lab Technicians' => $metrics['labTechs'],
            'Pharmacists' => $metrics['pharmacists'],
            'Receptionists' => $metrics['receptionists'],
        ] as $label => $value)
            <div class="bg-white p-4 rounded shadow"><div class="text-xs text-gray-500">{{ $label }}</div><div class="text-2xl font-semibold">{{ $value }}</div></div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-white p-4 rounded shadow md:col-span-2">
            <div class="font-semibold mb-2">Yearly Income Expense Chart ({{ now()->year }})</div>
            <canvas id="incomeExpenseChart" height="120"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="font-semibold mb-2">Notice Boards</div>
            <ul class="text-sm text-gray-700 space-y-2">
                @forelse($notices as $notice)
                    <li>{{ $notice['title'] }}</li>
                @empty
                    <li class="text-gray-500">No notices</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <div class="bg-white p-4 rounded shadow">
            <div class="font-semibold mb-2">Enquiries</div>
            <div class="text-gray-500">No enquiries yet</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="font-semibold mb-2">Appointments</div>
            <div class="text-gray-500">No data available</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('incomeExpenseChart');
            if (!ctx) return;
            import('https://cdn.jsdelivr.net/npm/chart.js').then(module => {
                const Chart = module.default;
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chart['labels']),
                        datasets: [
                            {label: 'Income', data: @json($chart['income']), borderColor: '#4f46e5', tension: 0.3},
                            {label: 'Expenses', data: @json($chart['expenses']), borderColor: '#9333ea', tension: 0.3},
                        ]
                    },
                    options: {responsive: true, maintainAspectRatio: false}
                });
            });
        });
    </script>
@endsection


