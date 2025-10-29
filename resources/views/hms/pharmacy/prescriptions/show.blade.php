<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="hover:text-green-600">Prescriptions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Prescription #{{ $prescription->id }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-prescription text-green-600 mr-3"></i>
                            Prescription Details
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Patient: {{ $prescription->patient->full_name ?? 'N/A' }} | 
                            Doctor: Dr. {{ $prescription->doctor ? $prescription->doctor->first_name . ' ' . $prescription->doctor->last_name : 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.pharmacy.prescriptions.edit', $prescription) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        <button onclick="window.print()" 
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                            <i class="fa fa-print mr-2"></i> Print
                        </button>
                        <a href="{{ route('hms.pharmacy.prescriptions.index') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Prescription Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Patient & Doctor Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-user text-green-600 mr-2"></i>
                                Patient & Doctor Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Patient Details</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <i class="fa fa-user text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $prescription->patient->full_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fa fa-id-card text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $prescription->patient->patient_no ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fa fa-calendar text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $prescription->patient->dob ? $prescription->patient->dob->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fa fa-phone text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $prescription->patient->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Doctor Details</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <i class="fa fa-user-md text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">
                                                Dr. {{ $prescription->doctor ? $prescription->doctor->first_name . ' ' . $prescription->doctor->last_name : 'N/A' }}
                                            </span>
                                        </div>
                                        @if($prescription->doctor && $prescription->doctor->department)
                                            <div class="flex items-center">
                                                <i class="fa fa-building text-gray-400 w-4 mr-3"></i>
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $prescription->doctor->department->name }}</span>
                                            </div>
                                        @endif
                                        @if($prescription->doctor && $prescription->doctor->qualification)
                                            <div class="flex items-center">
                                                <i class="fa fa-graduation-cap text-gray-400 w-4 mr-3"></i>
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $prescription->doctor->qualification }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    @if($prescription->diagnosis || $prescription->symptoms)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa fa-stethoscope text-red-600 mr-2"></i>
                                    Medical Information
                                </h3>
                                <div class="space-y-4">
                                    @if($prescription->diagnosis)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnosis</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $prescription->diagnosis }}</p>
                                        </div>
                                    @endif
                                    @if($prescription->symptoms)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Symptoms</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $prescription->symptoms }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Prescribed Medications -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-pills text-blue-600 mr-2"></i>
                                Prescribed Medications
                            </h3>
                            <div class="space-y-4">
                                @forelse($prescription->items as $item)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fa fa-capsules text-blue-600 dark:text-blue-400"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $item->medicine->name ?? 'Unknown Medicine' }}
                                                        </h4>
                                                        @if($item->medicine && $item->medicine->strength)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->medicine->strength }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Dosage</label>
                                                        <p class="text-sm text-gray-900 dark:text-white">{{ $item->dosage }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Frequency</label>
                                                        <p class="text-sm text-gray-900 dark:text-white">{{ $item->frequency }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Duration</label>
                                                        <p class="text-sm text-gray-900 dark:text-white">{{ $item->duration_days }} days</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Quantity</label>
                                                        <p class="text-sm text-gray-900 dark:text-white">{{ $item->quantity }}</p>
                                                    </div>
                                                </div>
                                                
                                                @if($item->instructions)
                                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                                        <label class="block text-xs font-medium text-yellow-700 dark:text-yellow-300 mb-1">
                                                            <i class="fa fa-info-circle mr-1"></i> Instructions
                                                        </label>
                                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $item->instructions }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i class="fa fa-pills text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No medications prescribed</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    @if($prescription->notes)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa fa-sticky-note text-yellow-600 mr-2"></i>
                                    Additional Notes
                                </h3>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $prescription->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Prescription Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prescription Status</h3>
                            <div class="text-center">
                                @php
                                    $statusColor = match($prescription->status) {
                                        'active' => 'green',
                                        'completed' => 'blue',
                                        'cancelled' => 'red',
                                        default => 'gray'
                                    };
                                @endphp
                                <div class="p-4 rounded-lg bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900">
                                    <i class="fa fa-prescription-bottle text-3xl text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</p>
                                    <p class="text-xl font-bold text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 capitalize">
                                        {{ $prescription->status }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prescription Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prescription Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Prescription ID:</span>
                                    <span class="text-gray-900 dark:text-white font-mono">#{{ $prescription->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $prescription->prescription_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Time:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $prescription->prescription_date->format('h:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Medicines:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $prescription->items->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Created:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $prescription->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('hms.pharmacy.prescriptions.edit', $prescription) }}" 
                                   class="w-full flex items-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                                    <i class="fa fa-edit text-blue-600 mr-3"></i>
                                    <span class="text-sm font-medium text-blue-900 dark:text-blue-200">Edit Prescription</span>
                                </a>
                                <button onclick="window.print()" 
                                        class="w-full flex items-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors">
                                    <i class="fa fa-print text-purple-600 mr-3"></i>
                                    <span class="text-sm font-medium text-purple-900 dark:text-purple-200">Print Prescription</span>
                                </button>
                                <button onclick="alert('Email prescription feature coming soon!')" 
                                        class="w-full flex items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                                    <i class="fa fa-envelope text-green-600 mr-3"></i>
                                    <span class="text-sm font-medium text-green-900 dark:text-green-200">Email to Patient</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Medical History -->
                    @if($prescription->patient)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Patient History</h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Prescriptions:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $prescription->patient->prescriptions->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Last Visit:</span>
                                        <span class="text-gray-900 dark:text-white">
                                            {{ $prescription->patient->prescriptions->latest()->first()?->prescription_date->format('M d, Y') ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('hms.patients.show', $prescription->patient) }}" 
                                       class="w-full flex items-center justify-center p-2 bg-teal-50 dark:bg-teal-900 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-800 transition-colors">
                                        <i class="fa fa-user text-teal-600 mr-2"></i>
                                        <span class="text-sm font-medium text-teal-900 dark:text-teal-200">View Patient Profile</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-white, .bg-gray-800 { background: white !important; }
            .text-gray-900, .text-gray-800, .text-gray-700, .text-gray-600, .text-gray-500, .text-gray-400 { color: black !important; }
            .border-gray-200, .border-gray-300, .border-gray-400, .border-gray-500, .border-gray-600, .border-gray-700 { border-color: black !important; }
        }
    </style>
</x-app-layout>