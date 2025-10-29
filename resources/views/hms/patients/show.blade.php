<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-injured text-blue-600 mr-3"></i>
                        Patient Details
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Complete patient information and medical records</p>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $hasInsurance = \App\Models\PatientInsurance::where('patient_id', $patient->id)->where('is_active', true)->exists();
                    @endphp
                    @if($hasInsurance)
                        <a href="{{ route('biometric.index', ['patient_id' => $patient->id]) }}" 
                           class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg"
                           title="Enroll Biometric for Insurance Patient">
                            <i class="fas fa-fingerprint mr-2"></i> Enroll Biometric
                        </a>
                    @endif
                    <a href="{{ route('hms.patients.edit', $patient) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.patients.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Insurance & Biometric Notice -->
            @php
                $hasInsurance = \App\Models\PatientInsurance::where('patient_id', $patient->id)->where('is_active', true)->exists();
            @endphp
            @if($hasInsurance)
                @php
                    $insurance = \App\Models\PatientInsurance::where('patient_id', $patient->id)->where('is_active', true)->with('provider')->first();
                @endphp
                <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start">
                            <i class="fa fa-shield-alt mr-3 mt-1"></i>
                            <div>
                                <strong>Insurance Patient:</strong> {{ $insurance->provider->name ?? 'Insurance' }} 
                                (Policy: {{ $insurance->policy_number ?? 'N/A' }})
                                <p class="text-sm mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Biometric enrollment is recommended for faster identification and check-in. 
                                    <a href="{{ route('biometric.index', ['patient_id' => $patient->id]) }}" 
                                       class="underline font-semibold ml-1">
                                        Enroll now
                                    </a>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('biometric.index', ['patient_id' => $patient->id]) }}" 
                           class="ml-4 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                            <i class="fas fa-fingerprint mr-1"></i> Enroll
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Patient Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                            </div>
                            <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ $patient->full_name }}</h2>
                            <p class="text-gray-600 dark:text-gray-400">Patient ID: {{ $patient->patient_no }}</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-venus-mars text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Gender</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $patient->gender ? ucfirst($patient->gender) : 'Not specified' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-birthday-cake text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Age</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($patient->dob)
                                        {{ \Carbon\Carbon::parse($patient->dob)->age }} years
                                    @else
                                        Not specified
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-calendar text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Registered</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $patient->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('hms.patients.id-card', $patient) }}" target="_blank" class="w-full flex items-center justify-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition">                                                                               
                                    <i class="fa fa-id-card mr-2"></i> Download ID Card                                                                   
                                </a>
                                <button class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                    <i class="fa fa-calendar-plus mr-2"></i> Book Appointment
                                </button>
                                <button class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                    <i class="fa fa-prescription mr-2"></i> New Prescription
                                </button>
                                <button class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                                    <i class="fa fa-flask mr-2"></i> Lab Tests
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Details & Records -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-address-book text-blue-600 mr-2"></i> Contact Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email Address</label>
                                <div class="flex items-center text-gray-900 dark:text-white">
                                    <i class="fa fa-envelope text-blue-600 mr-2"></i>
                                    {{ $patient->email ?: 'Not provided' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Phone Number</label>
                                <div class="flex items-center text-gray-900 dark:text-white">
                                    <i class="fa fa-phone text-blue-600 mr-2"></i>
                                    {{ $patient->phone ?: 'Not provided' }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Address</label>
                                <div class="flex items-start text-gray-900 dark:text-white">
                                    <i class="fa fa-map-marker-alt text-blue-600 mr-2 mt-1"></i>
                                    <span>{{ $patient->address ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-user text-blue-600 mr-2"></i> Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Patient Number</label>
                                <div class="text-gray-900 dark:text-white font-mono">{{ $patient->patient_no }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Full Name</label>
                                <div class="text-gray-900 dark:text-white">{{ $patient->full_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Date of Birth</label>
                                <div class="text-gray-900 dark:text-white">
                                    @if($patient->dob)
                                        {{ \Carbon\Carbon::parse($patient->dob)->format('F d, Y') }}
                                        <span class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($patient->dob)->age }} years old)</span>
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Gender</label>
                                <div class="text-gray-900 dark:text-white">{{ $patient->gender ? ucfirst($patient->gender) : 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Records (Placeholder) -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-notes-medical text-blue-600 mr-2"></i> Medical Records
                        </h3>
                        
                        <!-- Tabs -->
                        <div x-data="{ activeTab: 'appointments' }" class="mt-4">
                            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
                                <button @click="activeTab = 'appointments'" 
                                        :class="activeTab === 'appointments' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm">
                                    <i class="fa fa-calendar-alt mr-1"></i> Appointments
                                </button>
                                <button @click="activeTab = 'prescriptions'" 
                                        :class="activeTab === 'prescriptions' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm">
                                    <i class="fa fa-prescription mr-1"></i> Prescriptions
                                </button>
                                <button @click="activeTab = 'lab'" 
                                        :class="activeTab === 'lab' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm">
                                    <i class="fa fa-flask mr-1"></i> Lab Results
                                </button>
                                <button @click="activeTab = 'visits'" 
                                        :class="activeTab === 'visits' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm">
                                    <i class="fa fa-hospital mr-1"></i> Visits
                                </button>
                            </div>

                            <!-- Tab Content -->
                            <div x-show="activeTab === 'appointments'" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-calendar-times text-4xl mb-2"></i>
                                <p>No appointments scheduled</p>
                            </div>
                            <div x-show="activeTab === 'prescriptions'" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-prescription text-4xl mb-2"></i>
                                <p>No prescriptions on record</p>
                            </div>
                            <div x-show="activeTab === 'lab'" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-vial text-4xl mb-2"></i>
                                <p>No lab results available</p>
                            </div>
                            <div x-show="activeTab === 'visits'" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-hospital-user text-4xl mb-2"></i>
                                <p>No visit records found</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

