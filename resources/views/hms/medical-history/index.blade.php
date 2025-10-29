<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-file-medical text-indigo-600 mr-3"></i>
                    Medical History & Vitals
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Patient medical records and history</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Patients</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_patients'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">With Records</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['with_history'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-file-medical-alt text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Chronic Conditions</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['chronic_conditions'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-heartbeat text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Recent Updates (7d)</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['recent_updates'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-clock text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.medical-history.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by patient name or number..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                        <i class="fa fa-search mr-2"></i> Search
                    </button>
                    <a href="{{ route('hms.medical-history.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-redo"></i>
                    </a>
                </form>
            </div>

            <!-- Patients List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($patients as $patient)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                        {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $patient->full_name }}
                                        </h3>
                                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            <span><i class="fa fa-id-badge mr-1"></i> {{ $patient->patient_no }}</span>
                                            <span><i class="fa fa-birthday-cake mr-1"></i> {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}y)</span>
                                            <span><i class="fa fa-phone mr-1"></i> {{ $patient->phone }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('hms.medical-history.show', $patient->id) }}" 
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                        <i class="fa fa-eye mr-1"></i> View History
                                    </a>
                                </div>
                            </div>

                            @if($patient->medicalHistories->count() > 0)
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            Recent Medical Records ({{ $patient->medicalHistories->count() }})
                                        </h4>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($patient->medicalHistories->take(3) as $history)
                                            <div class="flex items-start gap-2 text-sm">
                                                <i class="fa fa-check-circle text-indigo-600 mt-1"></i>
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $history->condition }}</span>
                                                    @if($history->is_chronic)
                                                        <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-800 text-xs rounded-full">Chronic</span>
                                                    @endif
                                                    <p class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($history->recorded_date)->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    <i class="fa fa-info-circle mr-2"></i>
                                    No medical history records found
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-file-medical text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No patients found</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($patients->hasPages())
                <div class="mt-6">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

