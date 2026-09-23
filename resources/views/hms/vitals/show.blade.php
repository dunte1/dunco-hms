<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.vitals.index') }}" class="hover:text-red-600">Vitals</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Record #{{ $vital->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-heartbeat text-red-600 mr-3"></i>
                        Vitals Record #{{ str_pad($vital->id, 5, '0', STR_PAD_LEFT) }}
                    </h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.vitals.edit', $vital) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('hms.vitals.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Vitals Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fa fa-heartbeat text-red-600 mr-2"></i> Vital Signs
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Temperature</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->temperature ?? '-' }}&deg;F</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Blood Pressure</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->systolic_bp ?? '-' }}/{{ $vital->diastolic_bp ?? '-' }} <span class="text-sm font-normal text-gray-500">mmHg</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Pulse Rate</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->pulse_rate ?? '-' }} <span class="text-sm font-normal text-gray-500">bpm</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Respiratory Rate</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->respiratory_rate ?? '-' }} <span class="text-sm font-normal text-gray-500">/min</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">O2 Saturation</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->oxygen_saturation ?? '-' }}<span class="text-sm font-normal text-gray-500">%</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Blood Glucose</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->blood_glucose ?? '-' }} <span class="text-sm font-normal text-gray-500">mg/dL</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Weight</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->weight_kg ?? '-' }} <span class="text-sm font-normal text-gray-500">kg</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Height</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $vital->height_cm ?? '-' }} <span class="text-sm font-normal text-gray-500">cm</span></p>
                            </div>
                        </div>
                    </div>

                    @if($vital->notes)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fa fa-sticky-note text-amber-600 mr-2"></i> Notes
                            </h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $vital->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-6">
                    <!-- Patient Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-user-injured text-blue-600 mr-2"></i> Patient Info
                        </h3>
                        <div class="text-sm space-y-3">
                            <p class="font-medium text-gray-900 dark:text-white text-base">
                                {{ $vital->patient->first_name ?? '' }} {{ $vital->patient->last_name ?? 'N/A' }}
                            </p>
                            @if($vital->patient->patient_no)
                                <p class="text-gray-600 dark:text-gray-400">
                                    <i class="fa fa-id-card mr-1"></i> {{ $vital->patient->patient_no }}
                                </p>
                            @endif
                            @if($vital->patient->phone)
                                <p class="text-gray-600 dark:text-gray-400">
                                    <i class="fa fa-phone mr-1"></i> {{ $vital->patient->phone }}
                                </p>
                            @endif
                            @if($vital->patient->email)
                                <p class="text-gray-600 dark:text-gray-400">
                                    <i class="fa fa-envelope mr-1"></i> {{ $vital->patient->email }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Recording Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-info-circle text-green-600 mr-2"></i> Recording Info
                        </h3>
                        <div class="text-sm space-y-3">
                            <div>
                                <span class="text-gray-500">Recorded By:</span>
                                <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $vital->recorded_by ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Date & Time:</span>
                                <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $vital->recorded_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
                            </div>
                            @if($vital->opd_visit_id)
                                <div>
                                    <span class="text-gray-500">OPD Visit:</span>
                                    <a href="{{ route('hms.opd.show', $vital->opd_visit_id) }}" class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 ml-1">
                                        #{{ $vital->opd_visit_id }}
                                    </a>
                                </div>
                            @endif
                            @if($vital->ipd_admission_id)
                                <div>
                                    <span class="text-gray-500">IPD Admission:</span>
                                    <a href="{{ route('hms.ipd.show', $vital->ipd_admission_id) }}" class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 ml-1">
                                        #{{ $vital->ipd_admission_id }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
