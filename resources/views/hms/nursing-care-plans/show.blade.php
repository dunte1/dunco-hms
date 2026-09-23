<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.nursing-care-plans.index') }}" class="hover:text-cyan-600">Nursing Care Plans</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $carePlan->care_plan_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-clipboard-list text-cyan-600 mr-3"></i>Nursing Care Plan Details
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $carePlan->care_plan_number }} |
                            Patient: {{ $carePlan->patient->first_name ?? '' }} {{ $carePlan->patient->last_name ?? '' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.nursing-care-plans.edit', $carePlan) }}"
                           class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        <a href="{{ route('hms.nursing-care-plans.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-stethoscope text-cyan-600 mr-2"></i>
                                Nursing Diagnosis
                            </h3>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $carePlan->nursing_diagnosis }}</div>
                        </div>
                    </div>

                    @if($carePlan->goal)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-bullseye text-blue-600 mr-2"></i>
                                Goal
                            </h3>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $carePlan->goal }}</div>
                        </div>
                    </div>
                    @endif

                    @if($carePlan->interventions)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-hand-holding-medical text-purple-600 mr-2"></i>
                                Interventions
                            </h3>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $carePlan->interventions }}</div>
                        </div>
                    </div>
                    @endif

                    @if($carePlan->expected_outcome)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-check-double text-green-600 mr-2"></i>
                                Expected Outcome
                            </h3>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $carePlan->expected_outcome }}</div>
                        </div>
                    </div>
                    @endif

                    @if($carePlan->actual_outcome)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-clipboard-check text-amber-600 mr-2"></i>
                                Actual Outcome
                            </h3>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $carePlan->actual_outcome }}</div>
                        </div>
                    </div>
                    @endif

                    @if($carePlan->evaluation)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-chart-line text-teal-600 mr-2"></i>
                                Evaluation
                            </h3>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $carePlan->evaluation }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="space-y-6">

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h3>
                            <div class="text-center">
                                @php
                                    $statusColor = match($carePlan->status) {
                                        'active' => 'green',
                                        'completed' => 'blue',
                                        'cancelled' => 'red',
                                        default => 'gray'
                                    };
                                @endphp
                                <div class="p-4 rounded-lg bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900">
                                    <i class="fa fa-clipboard-list text-3xl text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</p>
                                    <p class="text-xl font-bold text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 capitalize">
                                        {{ ucfirst($carePlan->status) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Plan Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Plan #:</span>
                                    <span class="text-gray-900 dark:text-white font-mono">{{ $carePlan->care_plan_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Created:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $carePlan->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $carePlan->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assigned Nurse</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <i class="fa fa-user-nurse text-gray-400 w-4 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white">{{ $carePlan->assignedNurse?->name ?? 'Not Assigned' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-sky-500 to-sky-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Patient Info</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <i class="fa fa-user text-gray-400 w-4 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white">{{ $carePlan->patient->first_name ?? '' }} {{ $carePlan->patient->last_name ?? '' }}</span>
                                </div>
                                @if($carePlan->ipdAdmission)
                                <div class="flex items-center">
                                    <i class="fa fa-bed text-gray-400 w-4 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white">{{ $carePlan->ipdAdmission->admission_number }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('hms.nursing-care-plans.edit', $carePlan) }}"
                                   class="w-full flex items-center p-3 bg-cyan-50 dark:bg-cyan-900 rounded-lg hover:bg-cyan-100 dark:hover:bg-cyan-800 transition-colors">
                                    <i class="fa fa-edit text-cyan-600 mr-3"></i>
                                    <span class="text-sm font-medium text-cyan-900 dark:text-cyan-200">Edit Care Plan</span>
                                </a>
                                <a href="{{ route('hms.nursing-care-plans.index') }}"
                                   class="w-full flex items-center p-3 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <i class="fa fa-arrow-left text-gray-600 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">Back to List</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
