<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb & Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.triage.index') }}" class="hover:text-red-600">Triage</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $triage->triage_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        <i class="fa fa-heartbeat text-red-600 mr-3"></i>Triage #{{ $triage->triage_number }}
                    </h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.triage.edit', $triage) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('hms.triage.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif

            <!-- Patient Info & Priority Badge -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fa fa-user text-red-600 mr-2"></i>Patient Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Patient Name:</span>
                                <span class="font-medium text-gray-900 dark:text-white ml-2">
                                    {{ $triage->patient->first_name ?? 'N/A' }} {{ $triage->patient->last_name ?? '' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Patient No:</span>
                                <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $triage->patient->patient_no ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Triage #:</span>
                                <span class="font-medium text-red-600 dark:text-red-400 ml-2">{{ $triage->triage_number }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $triage->triaged_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Triaged By:</span>
                                <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $triage->triager->name ?? 'N/A' }}</span>
                            </div>
                            @if($triage->opdVisit)
                                <div>
                                    <span class="text-gray-500">OPD Visit:</span>
                                    <span class="font-medium text-gray-900 dark:text-white ml-2">#{{ $triage->opd_visit_id }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center w-full">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Priority Level</h3>
                        <span class="inline-block px-6 py-3 text-lg font-bold rounded-full
                            {{ $triage->priority_level === 'emergency' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                               ($triage->priority_level === 'urgent' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                               ($triage->priority_level === 'semi_urgent' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                               'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200')) }}">
                            <i class="fa fa-{{ $triage->priority_level === 'emergency' ? 'exclamation-triangle' : ($triage->priority_level === 'urgent' ? 'bolt' : ($triage->priority_level === 'semi_urgent' ? 'clock' : 'check-circle')) }} mr-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $triage->priority_level)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Vitals Grid -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fa fa-heartbeat text-red-600 mr-2"></i>Vitals
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-thermometer-half text-red-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Temperature</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->temperature ? $triage->temperature . '°C' : '-' }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-heartbeat text-red-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pulse Rate</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->pulse_rate ? $triage->pulse_rate . ' bpm' : '-' }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-tachometer-alt text-blue-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Blood Pressure</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->systolic_bp && $triage->diastolic_bp ? $triage->systolic_bp . '/' . $triage->diastolic_bp . ' mmHg' : '-' }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-wind text-cyan-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Respiratory Rate</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->respiratory_rate ? $triage->respiratory_rate . ' /min' : '-' }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-lungs text-green-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">O₂ Saturation</p>
                        <p class="text-lg font-bold {{ ($triage->oxygen_saturation && $triage->oxygen_saturation < 95) ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                            {{ $triage->oxygen_saturation ? $triage->oxygen_saturation . '%' : '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-tint text-purple-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Blood Glucose</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->blood_glucose ? $triage->blood_glucose . ' mg/dL' : '-' }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-weight text-orange-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Weight</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->weight_kg ? $triage->weight_kg . ' kg' : '-' }}</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                        <i class="fa fa-ruler-vertical text-teal-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Height</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $triage->height_cm ? $triage->height_cm . ' cm' : '-' }}</p>
                    </div>
                </div>

                @if($triage->bmi)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-2">
                        <span class="text-sm text-gray-500">BMI:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $triage->bmi }} kg/m²</span>
                        <span class="text-xs text-gray-500">({{ $triage->bmi < 18.5 ? 'Underweight' : ($triage->bmi < 25 ? 'Normal' : ($triage->bmi < 30 ? 'Overweight' : 'Obese')) }})</span>
                    </div>
                @endif
            </div>

            <!-- Chief Complaint & Notes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        <i class="fa fa-comment-medical text-red-600 mr-2"></i>Chief Complaint
                    </h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                        {{ $triage->chief_complaint ?? 'No chief complaint recorded' }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        <i class="fa fa-sticky-note text-red-600 mr-2"></i>Triage Notes
                    </h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                        {{ $triage->triage_notes ?? 'No notes recorded' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
