<x-app-layout>
    <div class="p-6">
        <!-- Welcome Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Patients -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_patients'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <i class="fas fa-user-injured text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Doctors -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Doctors</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_doctors'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <i class="fas fa-user-md text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Available Beds -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Available Beds</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['available_beds'] }}/{{ $stats['total_beds'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <i class="fas fa-bed text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['todays_appointments'] }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <i class="fas fa-calendar-check text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Nurses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nurses</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_nurses'] }}</p>
                    </div>
                    <i class="fas fa-user-nurse text-xl text-blue-500"></i>
                </div>
            </div>

            <!-- Pharmacists -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pharmacists</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_pharmacists'] }}</p>
                    </div>
                    <i class="fas fa-pills text-xl text-green-500"></i>
                </div>
            </div>

            <!-- Lab Technicians -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lab Technicians</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_lab_technicians'] }}</p>
                    </div>
                    <i class="fas fa-microscope text-xl text-purple-500"></i>
                </div>
            </div>

            <!-- Receptionists -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Receptionists</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_receptionists'] }}</p>
                    </div>
                    <i class="fas fa-user-tie text-xl text-orange-500"></i>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Revenue Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-dollar-sign mr-2"></i> Revenue Summary
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span>Total Invoices</span>
                        <span class="text-xl font-bold">${{ number_format($stats['total_invoices'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Total Payments</span>
                        <span class="text-xl font-bold">${{ number_format($stats['total_payments'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Outstanding</span>
                        <span class="text-xl font-bold">${{ number_format($stats['outstanding_balance'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i> Quick Actions
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('hms.patients.create') }}" class="flex items-center justify-center p-3 bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 rounded-lg transition">
                        <i class="fas fa-user-plus mr-2 text-blue-600 dark:text-blue-400"></i>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Add Patient</span>
                    </a>
                    <a href="{{ route('hms.appointments.create') }}" class="flex items-center justify-center p-3 bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 rounded-lg transition">
                        <i class="fas fa-calendar-plus mr-2 text-green-600 dark:text-green-400"></i>
                        <span class="text-sm font-medium text-green-700 dark:text-green-300">New Appointment</span>
                    </a>
                    <a href="{{ route('hms.billing.index') }}" class="flex items-center justify-center p-3 bg-purple-50 dark:bg-purple-900 hover:bg-purple-100 dark:hover:bg-purple-800 rounded-lg transition">
                        <i class="fas fa-file-invoice mr-2 text-purple-600 dark:text-purple-400"></i>
                        <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Generate Bill</span>
                    </a>
                    <a href="{{ route('hms.laboratory.index') }}" class="flex items-center justify-center p-3 bg-orange-50 dark:bg-orange-900 hover:bg-orange-100 dark:hover:bg-orange-800 rounded-lg transition">
                        <i class="fas fa-flask mr-2 text-orange-600 dark:text-orange-400"></i>
                        <span class="text-sm font-medium text-orange-700 dark:text-orange-300">Lab Tests</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-calendar mr-2 text-blue-500"></i> Recent Appointments
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($recentAppointments as $appointment)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Dr. {{ $appointment->doctor->full_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $appointment->scheduled_at ? $appointment->scheduled_at->format('M d, Y') : 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">{{ $appointment->scheduled_at ? $appointment->scheduled_at->format('h:i A') : '' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No recent appointments</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Lab Tests -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-flask mr-2 text-purple-500"></i> Recent Lab Requests
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($recentLabRequests as $labTest)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                        <i class="fas fa-microscope text-purple-600 dark:text-purple-400 text-sm"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $labTest->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Request #{{ $labTest->id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $labTest->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                    {{ ucfirst($labTest->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No recent lab requests</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
