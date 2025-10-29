@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Dashboard</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Real-time overview of hospital operations and statistics</p>
    </div>

    <!-- Financial Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Invoice Amount</div>
            <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">${{ number_format($metrics['invoiceAmount'], 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Outstanding Bills</div>
            <div class="text-2xl font-semibold text-red-600">${{ number_format($metrics['billAmount'], 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Payments</div>
            <div class="text-2xl font-semibold text-green-600">${{ number_format($metrics['paymentAmount'], 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Advance Payments</div>
            <div class="text-2xl font-semibold text-blue-600">${{ number_format($metrics['advanceAmount'], 2) }}</div>
        </div>
    </div>

    <!-- Operational Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Patients</div>
            <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ number_format($metrics['patients']) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Doctors</div>
            <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ number_format($metrics['doctors']) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Beds Available</div>
            <div class="text-2xl font-semibold text-green-600">{{ $metrics['availableBeds'] }}/{{ $metrics['totalBeds'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Today's Appointments</div>
            <div class="text-2xl font-semibold text-blue-600">{{ $metrics['todayAppointments'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Pending Appointments</div>
            <div class="text-2xl font-semibold text-yellow-600">{{ $metrics['pendingAppointments'] }}</div>
        </div>
    </div>

    <!-- Staff Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Nurses</div>
            <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metrics['nurses'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Pharmacists</div>
            <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metrics['pharmacists'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Lab Technicians</div>
            <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metrics['labTechs'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Receptionists</div>
            <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metrics['receptionists'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Accountants</div>
            <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metrics['accountants'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Admins</div>
            <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metrics['admins'] }}</div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Income/Expense Chart -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Yearly Income & Expenses ({{ now()->year }})</h3>
            <canvas id="incomeExpenseChart" height="120"></canvas>
        </div>

        <!-- Recent Notices -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Recent Notices</h3>
            <div class="space-y-3">
                @forelse($notices as $notice)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $notice->title }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $notice->published_at ? $notice->published_at->format('M d, Y') : 'No date' }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No notices available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Patients -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Recent Patients</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Patient</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentPatients as $patient)
                            <tr>
                                <td class="px-3 py-2 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-1 text-xs rounded {{ $patient->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $patient->status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $patient->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">No patients found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Recent Appointments</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Patient</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Doctor</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentAppointments as $appointment)
                            <tr>
                                <td class="px-3 py-2 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $appointment->doctor->first_name ?? 'N/A' }} {{ $appointment->doctor->last_name ?? '' }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">No appointments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enquiries -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Recent Enquiries</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Subject</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($enquiries as $enquiry)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">{{ $enquiry->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">{{ $enquiry->subject ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $enquiry->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">No enquiries found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('incomeExpenseChart');
        if (!ctx) return;
        
        import('https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js').then(module => {
            const Chart = module.Chart;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chart['labels']),
                    datasets: [
                        {
                            label: 'Income',
                            data: @json($chart['income']),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Expenses',
                            data: @json($chart['expenses']),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }).catch(() => {
            console.error('Failed to load Chart.js');
        });
    });
</script>
@endsection

