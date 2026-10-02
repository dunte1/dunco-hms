<x-app-layout>
    <div class="p-6">
        <!-- Welcome Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
        </div>

        <!-- My Work (role-aware) -->
        @if(!empty($myWork) && count($myWork))
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-tasks mr-2 text-indigo-500"></i>My Work</h3>
            </div>
            <div class="p-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                @foreach($myWork as $item)
                    @if($item['route'])
                        <a href="{{ $item['route'] }}" class="block p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                            <p class="text-xs text-gray-500">{{ $item['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $item['count'] }}</p>
                        </a>
                    @else
                        <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500">{{ $item['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $item['count'] }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Primary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('hms.patients.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_patients']) }}</p>
                        <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>+{{ $stats['new_patients_today'] }} today</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <i class="fas fa-user-injured text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('hms.appointments.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['todays_appointments']) }}</p>
                        <p class="text-xs text-blue-600 mt-1"><i class="fas fa-calendar mr-1"></i>{{ $stats['todays_opd_visits'] }} OPD visits</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <i class="fas fa-calendar-check text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('hms.billing.invoices.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Today's Revenue</p>
                        @php $currencySymbol = \App\Models\SystemSetting::get('currency_symbol', 'KES'); @endphp
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $currencySymbol }}{{ number_format($stats['total_payments'], 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-chart-line mr-1"></i>{{ $currencySymbol }}{{ number_format($stats['monthly_revenue'], 2) }} this month</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <i class="fas fa-dollar-sign text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('hms.ipd.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Active Inpatients</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['active_inpatients']) }}</p>
                        <p class="text-xs text-purple-600 mt-1"><i class="fas fa-bed mr-1"></i>{{ $stats['available_beds'] }}/{{ $stats['total_beds'] }} beds available</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <i class="fas fa-procedures text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Secondary KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900 rounded-lg mr-3"><i class="fas fa-user-md text-blue-600"></i></div>
                    <div><p class="text-xs text-gray-500">Doctors</p><p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_doctors'] }}</p></div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-pink-50 dark:bg-pink-900 rounded-lg mr-3"><i class="fas fa-user-nurse text-pink-600"></i></div>
                    <div><p class="text-xs text-gray-500">Nurses</p><p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_nurses'] }}</p></div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-50 dark:bg-green-900 rounded-lg mr-3"><i class="fas fa-pills text-green-600"></i></div>
                    <div><p class="text-xs text-gray-500">Pharmacists</p><p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_pharmacists'] }}</p></div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-50 dark:bg-purple-900 rounded-lg mr-3"><i class="fas fa-microscope text-purple-600"></i></div>
                    <div><p class="text-xs text-gray-500">Lab Techs</p><p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_lab_technicians'] }}</p></div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-50 dark:bg-yellow-900 rounded-lg mr-3"><i class="fas fa-user-tie text-yellow-600"></i></div>
                    <div><p class="text-xs text-gray-500">Receptionists</p><p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_receptionists'] }}</p></div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-red-50 dark:bg-red-900 rounded-lg mr-3"><i class="fas fa-exclamation-triangle text-red-600"></i></div>
                    <div><p class="text-xs text-gray-500">Outstanding</p><p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $currencySymbol }}{{ number_format($stats['outstanding_balance'], 0) }}</p></div>
                </div>
            </div>
        </div>

        <!-- Diagnostic Alerts Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <a href="{{ route('hms.laboratory.requests.index') }}" class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-flask text-yellow-600 mr-3 text-lg"></i>
                <div><p class="text-xs text-yellow-700 dark:text-yellow-300">Pending Lab</p><p class="text-sm font-bold text-yellow-800 dark:text-yellow-200">{{ $stats['pending_lab_requests'] }} requests</p></div>
            </a>
            <a href="{{ route('hms.radiology.requests.index') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-x-ray text-blue-600 mr-3 text-lg"></i>
                <div><p class="text-xs text-blue-700 dark:text-blue-300">Pending Radiology</p><p class="text-sm font-bold text-blue-800 dark:text-blue-200">{{ $stats['pending_radiology'] }} requests</p></div>
            </a>
            <a href="{{ route('hms.inventory.expiry-alerts') }}" class="flex items-center p-3 bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-800 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-clock text-orange-600 mr-3 text-lg"></i>
                <div><p class="text-xs text-orange-700 dark:text-orange-300">Expiring Stock</p><p class="text-sm font-bold text-orange-800 dark:text-orange-200">{{ $stats['expiring_items'] }} items (30d)</p></div>
            </a>
            <a href="{{ route('hms.insurance.claims.index') }}" class="flex items-center p-3 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 rounded-lg hover:bg-indigo-100 transition-colors">
                <i class="fas fa-file-medical text-indigo-600 mr-3 text-lg"></i>
                <div><p class="text-xs text-indigo-700 dark:text-indigo-300">Pending Claims</p><p class="text-sm font-bold text-indigo-800 dark:text-indigo-200">{{ $stats['pending_claims'] }} claims</p></div>
            </a>
        </div>

        <!-- Charts Row 1: Revenue + Patient Registrations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-chart-line mr-2 text-green-500"></i>Revenue Overview (12 Months)</h3>
                </div>
                <div class="p-5">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>

            <!-- Patient Registrations Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-user-plus mr-2 text-blue-500"></i>Patient Registrations (30 Days)</h3>
                </div>
                <div class="p-5">
                    <canvas id="patientChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Row 2: Appointments + OPD/IPD -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Appointments Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-calendar-alt mr-2 text-orange-500"></i>Appointments (7 Days)</h3>
                </div>
                <div class="p-5">
                    <canvas id="appointmentChart" height="200"></canvas>
                </div>
            </div>

            <!-- OPD vs IPD Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-procedures mr-2 text-purple-500"></i>OPD vs IPD (7 Days)</h3>
                </div>
                <div class="p-5">
                    <canvas id="visitChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions + Ward Occupancy -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-bolt mr-2 text-yellow-500"></i>Quick Actions</h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('hms.patients.create') }}" class="flex items-center justify-center p-3 bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 rounded-lg transition">
                            <i class="fas fa-user-plus mr-2 text-blue-600 dark:text-blue-400"></i>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Add Patient</span>
                        </a>
                        <a href="{{ route('hms.appointments.create') }}" class="flex items-center justify-center p-3 bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 rounded-lg transition">
                            <i class="fas fa-calendar-plus mr-2 text-green-600 dark:text-green-400"></i>
                            <span class="text-sm font-medium text-green-700 dark:text-green-300">New Appointment</span>
                        </a>
                        <a href="{{ route('hms.billing.invoices.create') }}" class="flex items-center justify-center p-3 bg-purple-50 dark:bg-purple-900 hover:bg-purple-100 dark:hover:bg-purple-800 rounded-lg transition">
                            <i class="fas fa-file-invoice mr-2 text-purple-600 dark:text-purple-400"></i>
                            <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Generate Bill</span>
                        </a>
                        <a href="{{ route('hms.laboratory.requests.index') }}" class="flex items-center justify-center p-3 bg-orange-50 dark:bg-orange-900 hover:bg-orange-100 dark:hover:bg-orange-800 rounded-lg transition">
                            <i class="fas fa-flask mr-2 text-orange-600 dark:text-orange-400"></i>
                            <span class="text-sm font-medium text-orange-700 dark:text-orange-300">Lab Tests</span>
                        </a>
                        <a href="{{ route('hms.pharmacy.prescriptions.create') }}" class="flex items-center justify-center p-3 bg-red-50 dark:bg-red-900 hover:bg-red-100 dark:hover:bg-red-800 rounded-lg transition">
                            <i class="fas fa-prescription mr-2 text-red-600 dark:text-red-400"></i>
                            <span class="text-sm font-medium text-red-700 dark:text-red-300">Prescription</span>
                        </a>
                        <a href="{{ route('hms.billing.payments.create') }}" class="flex items-center justify-center p-3 bg-teal-50 dark:bg-teal-900 hover:bg-teal-100 dark:hover:bg-teal-800 rounded-lg transition">
                            <i class="fas fa-credit-card mr-2 text-teal-600 dark:text-teal-400"></i>
                            <span class="text-sm font-medium text-teal-700 dark:text-teal-300">Record Payment</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Department Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-building mr-2 text-cyan-500"></i>Department Activity</h3>
                </div>
                <div class="p-5">
                    @if($departmentActivity->count() > 0)
                        @foreach($departmentActivity as $dept => $count)
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $dept }}</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-3">
                                <div class="bg-cyan-500 h-1.5 rounded-full" style="width: {{ $departmentActivity->first() > 0 ? ($count / $departmentActivity->first()) * 100 : 0 }}%"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No department activity this month.</p>
                    @endif
                </div>
            </div>

            <!-- Ward Occupancy -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-bed mr-2 text-indigo-500"></i>Ward Occupancy</h3>
                </div>
                <div class="p-5">
                    @if($wardOccupancy->count() > 0)
                        @foreach($wardOccupancy as $ward)
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $ward->name }}</span>
                                <span class="text-xs font-semibold {{ $ward->occupancy_rate > 90 ? 'text-red-600' : ($ward->occupancy_rate > 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $ward->occupied_beds }}/{{ $ward->total_beds }} ({{ $ward->occupancy_rate }}%)
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-3">
                                <div class="h-1.5 rounded-full {{ $ward->occupancy_rate > 90 ? 'bg-red-500' : ($ward->occupancy_rate > 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ $ward->occupancy_rate }}%"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No ward data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-calendar mr-2 text-blue-500"></i>Recent Appointments</h3>
                    <a href="{{ route('hms.appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="p-5">
                    @forelse($recentAppointments as $appointment)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Dr. {{ $appointment->doctor->full_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $appointment->scheduled_at ? $appointment->scheduled_at->format('M d') : 'N/A' }}</p>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : ($appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($appointment->status ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No recent appointments.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Lab Requests -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-flask mr-2 text-purple-500"></i>Recent Lab Requests</h3>
                    <a href="{{ route('hms.laboratory.requests.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="p-5">
                    @forelse($recentLabRequests as $labTest)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-microscope text-purple-600 dark:text-purple-400 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $labTest->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Request #{{ $labTest->request_number ?? $labTest->id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 text-xs rounded-full 
                                    {{ ($labTest->status ?? '') === 'completed' ? 'bg-green-100 text-green-800' : (($labTest->status ?? '') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($labTest->status ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No recent lab requests.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark') || window.matchMedia('(prefers-color-scheme: dark)').matches;
        const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($revenueChart, 'month')),
                    datasets: [{
                        label: 'Revenue',
                        data: @json(array_column($revenueChart, 'revenue')),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981',
                    }, {
                        label: 'Invoices',
                        data: @json(array_column($revenueChart, 'invoices')),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#3b82f6',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: textColor } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }

        // Patient Registrations Chart
        const patientCtx = document.getElementById('patientChart');
        if (patientCtx) {
            new Chart(patientCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($patientChart, 'date')),
                    datasets: [{
                        label: 'New Patients',
                        data: @json(array_column($patientChart, 'count')),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } },
                        x: { grid: { display: false }, ticks: { color: textColor, maxRotation: 45 } }
                    }
                }
            });
        }

        // Appointments Chart
        const apptCtx = document.getElementById('appointmentChart');
        if (apptCtx) {
            new Chart(apptCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($appointmentChart, 'day')),
                    datasets: [{
                        label: 'Completed',
                        data: @json(array_column($appointmentChart, 'completed')),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 4,
                    }, {
                        label: 'Pending',
                        data: @json(array_column($appointmentChart, 'pending')),
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: textColor } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }

        // OPD vs IPD Chart
        const visitCtx = document.getElementById('visitChart');
        if (visitCtx) {
            new Chart(visitCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($visitChart, 'day')),
                    datasets: [{
                        label: 'OPD Visits',
                        data: @json(array_column($visitChart, 'opd')),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }, {
                        label: 'IPD Admissions',
                        data: @json(array_column($visitChart, 'ipd')),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: textColor } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }
    });
    </script>
</x-app-layout>
