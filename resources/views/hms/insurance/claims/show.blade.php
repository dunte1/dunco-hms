<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.insurance.claims.index') }}" class="hover:text-blue-600">Insurance Claims</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $claim->claim_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-file-medical text-blue-600 mr-3"></i>
                            Insurance Claim Details
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Claim #{{ $claim->claim_number }} | 
                            Patient: {{ $claim->patient->full_name ?? 'N/A' }} | 
                            Provider: {{ $claim->patientInsurance->insuranceProvider->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.insurance.claims.edit', $claim) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        <button onclick="window.print()" 
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                            <i class="fa fa-print mr-2"></i> Print
                        </button>
                        <a href="{{ route('hms.insurance.claims.index') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Claim Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Claim Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-file-medical text-blue-600 mr-2"></i>
                                Claim Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claim Number</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $claim->claim_number }}</p>
                                </div>
                                @if($claim->insurance_reference)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Insurance Reference</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $claim->insurance_reference }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claim Date</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $claim->claim_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Date</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $claim->service_date->format('M d, Y') }}</p>
                                </div>
                                @if($claim->diagnosis_code)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnosis Code</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $claim->diagnosis_code }}</p>
                                    </div>
                                @endif
                                @if($claim->treatment_description)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Description</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $claim->treatment_description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Patient & Provider Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-user text-green-600 mr-2"></i>
                                Patient & Provider Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Patient Details</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <i class="fa fa-user text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patient->full_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fa fa-id-card text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patient->patient_no ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fa fa-calendar text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patient->dob ? $claim->patient->dob->format('M d, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fa fa-phone text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patient->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Insurance Provider</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <i class="fa fa-building text-gray-400 w-4 mr-3"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patientInsurance->insuranceProvider->name ?? 'N/A' }}</span>
                                        </div>
                                        @if($claim->patientInsurance->policy_number)
                                            <div class="flex items-center">
                                                <i class="fa fa-file-alt text-gray-400 w-4 mr-3"></i>
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patientInsurance->policy_number }}</span>
                                            </div>
                                        @endif
                                        @if($claim->patientInsurance->group_number)
                                            <div class="flex items-center">
                                                <i class="fa fa-users text-gray-400 w-4 mr-3"></i>
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patientInsurance->group_number }}</span>
                                            </div>
                                        @endif
                                        @if($claim->patientInsurance->coverage_percentage)
                                            <div class="flex items-center">
                                                <i class="fa fa-percentage text-gray-400 w-4 mr-3"></i>
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $claim->patientInsurance->coverage_percentage }}% Coverage</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-dollar-sign text-purple-600 mr-2"></i>
                                Financial Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                        <i class="fa fa-file-invoice-dollar text-blue-600 text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Claimed Amount</p>
                                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($claim->claimed_amount, 2) }}</p>
                                    </div>
                                </div>
                                @if($claim->approved_amount)
                                    <div class="text-center">
                                        <div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                            <i class="fa fa-check-circle text-green-600 text-2xl mb-2"></i>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Approved Amount</p>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($claim->approved_amount, 2) }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($claim->paid_amount)
                                    <div class="text-center">
                                        <div class="p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                                            <i class="fa fa-money-bill-wave text-purple-600 text-2xl mb-2"></i>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Paid Amount</p>
                                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">${{ number_format($claim->paid_amount, 2) }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            @if($claim->deductible_amount || $claim->copay_amount)
                                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if($claim->deductible_amount)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deductible Amount</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">${{ number_format($claim->deductible_amount, 2) }}</p>
                                        </div>
                                    @endif
                                    @if($claim->copay_amount)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Copay Amount</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">${{ number_format($claim->copay_amount, 2) }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($claim->notes || $claim->rejection_reason)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa fa-sticky-note text-yellow-600 mr-2"></i>
                                    Additional Information
                                </h3>
                                <div class="space-y-4">
                                    @if($claim->notes)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $claim->notes }}</p>
                                        </div>
                                    @endif
                                    @if($claim->rejection_reason)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rejection Reason</label>
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $claim->rejection_reason }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Claim Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Claim Status</h3>
                            <div class="text-center">
                                @php
                                    $statusColor = match($claim->status) {
                                        'pending' => 'yellow',
                                        'submitted' => 'blue',
                                        'under_review' => 'orange',
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                        'paid' => 'purple',
                                        default => 'gray'
                                    };
                                @endphp
                                <div class="p-4 rounded-lg bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900">
                                    <i class="fa fa-file-medical text-3xl text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</p>
                                    <p class="text-xl font-bold text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 capitalize">
                                        {{ ucwords(str_replace('_', ' ', $claim->status)) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Claim Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Claim Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Claim ID:</span>
                                    <span class="text-gray-900 dark:text-white font-mono">#{{ $claim->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Created:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $claim->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $claim->updated_at->format('M d, Y') }}</span>
                                </div>
                                @if($claim->submitted_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Submitted:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $claim->submitted_at->format('M d, Y') }}</span>
                                    </div>
                                @endif
                                @if($claim->processed_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Processed:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $claim->processed_at->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('hms.insurance.claims.edit', $claim) }}" 
                                   class="w-full flex items-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                                    <i class="fa fa-edit text-blue-600 mr-3"></i>
                                    <span class="text-sm font-medium text-blue-900 dark:text-blue-200">Edit Claim</span>
                                </a>
                                <button onclick="window.print()" 
                                        class="w-full flex items-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors">
                                    <i class="fa fa-print text-purple-600 mr-3"></i>
                                    <span class="text-sm font-medium text-purple-900 dark:text-purple-200">Print Claim</span>
                                </button>
                                <button onclick="alert('Email claim feature coming soon!')" 
                                        class="w-full flex items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                                    <i class="fa fa-envelope text-green-600 mr-3"></i>
                                    <span class="text-sm font-medium text-green-900 dark:text-green-200">Email to Provider</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Insurance History -->
                    @if($claim->patient)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Patient History</h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Claims:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $claim->patient->insuranceClaims->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Last Claim:</span>
                                        <span class="text-gray-900 dark:text-white">
                                            {{ $claim->patient->insuranceClaims->latest()->first()?->claim_date->format('M d, Y') ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('hms.patients.show', $claim->patient) }}" 
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
