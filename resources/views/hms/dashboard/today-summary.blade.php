<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📅 Today's Summary</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Appointments Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-check text-2xl"></i>
                        </div>
                        <span class="text-3xl font-bold">{{ $stats['appointments']['total'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Appointments</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Completed:</span>
                            <span class="font-medium">{{ $stats['appointments']['completed'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pending:</span>
                            <span class="font-medium">{{ $stats['appointments']['pending'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Cancelled:</span>
                            <span class="font-medium">{{ $stats['appointments']['cancelled'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Patients Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-injured text-2xl"></i>
                        </div>
                        <span class="text-3xl font-bold">{{ $stats['patients']['total_active'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Patients</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>New Registrations:</span>
                            <span class="font-medium">{{ $stats['patients']['new_registrations'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>OPD Visits:</span>
                            <span class="font-medium">{{ $stats['patients']['opd_visits'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>IPD Admissions:</span>
                            <span class="font-medium">{{ $stats['patients']['ipd_admissions'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Financial Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-dollar-sign text-2xl"></i>
                        </div>
                        <span class="text-2xl font-bold">${{ number_format($stats['financial']['total_revenue'], 2) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Revenue</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Invoices:</span>
                            <span class="font-medium">{{ $stats['financial']['invoices_generated'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payments:</span>
                            <span class="font-medium">{{ $stats['financial']['payments_received'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pending:</span>
                            <span class="font-medium">${{ number_format($stats['financial']['pending_amount'], 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Diagnostics Card -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-flask text-2xl"></i>
                        </div>
                        <span class="text-3xl font-bold">{{ $stats['diagnostics']['lab_tests'] + $stats['diagnostics']['radiology_tests'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Tests</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Lab Tests:</span>
                            <span class="font-medium">{{ $stats['diagnostics']['lab_tests'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Radiology Tests:</span>
                            <span class="font-medium">{{ $stats['diagnostics']['radiology_tests'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Completed:</span>
                            <span class="font-medium">{{ $stats['diagnostics']['completed_tests'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Appointments -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fa fa-calendar-alt text-blue-600 mr-2"></i>
                        Today's Appointments
                    </h2>
                    <div class="space-y-3">
                        @forelse($recentAppointments as $appointment)
                            <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <i class="fa fa-user-md text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $appointment->patient->name ?? 'N/A' }}
                                        </h4>
                                        <span class="text-xs px-2 py-1 rounded-full {{ 
                                            $appointment->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                            ($appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
                                        }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        <i class="fa fa-user-doctor mr-1"></i> Dr. {{ $appointment->doctor->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <i class="fa fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-calendar-times text-4xl mb-2"></i>
                                <p>No appointments scheduled for today</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent OPD Visits -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fa fa-hospital-user text-green-600 mr-2"></i>
                        Recent OPD Visits
                    </h2>
                    <div class="space-y-3">
                        @forelse($recentOpdVisits as $visit)
                            <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fa fa-user-injured text-green-600 dark:text-green-400"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $visit->patient->name ?? 'N/A' }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        <i class="fa fa-hashtag mr-1"></i> Visit ID: #{{ $visit->id }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <i class="fa fa-clock mr-1"></i> {{ $visit->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-hospital text-4xl mb-2"></i>
                                <p>No OPD visits recorded today</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

