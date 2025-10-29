<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.radiology.requests.index') }}" class="hover:text-purple-600">Radiology Requests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $radiologyRequest->request_number }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-x-ray text-purple-600 mr-3"></i>
                    Radiology Request Details
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $radiologyRequest->request_number }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Request Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Request Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Request Number</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $radiologyRequest->request_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Request Date</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($radiologyRequest->request_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Patient</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $radiologyRequest->patient->full_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Requesting Doctor</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        @if($radiologyRequest->doctor)
                                            Dr. {{ $radiologyRequest->doctor->first_name }} {{ $radiologyRequest->doctor->last_name }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                @if($radiologyRequest->appointment_date)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Appointment Date</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($radiologyRequest->appointment_date)->format('M d, Y') }}</p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $radiologyRequest->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($radiologyRequest->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($radiologyRequest->status ?? 'pending') }}
                                    </span>
                                </div>
                            </div>

                            @if($radiologyRequest->clinical_notes)
                                <div class="mt-4 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Clinical Notes</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $radiologyRequest->clinical_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Test Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Requested Test</h3>
                            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $radiologyRequest->radiologyTest->test_name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                            {{ $radiologyRequest->radiologyTest->category->name ?? 'N/A' }}
                                        </p>
                                        @if($radiologyRequest->radiologyTest->description)
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                                {{ $radiologyRequest->radiologyTest->description }}
                                            </p>
                                        @endif
                                        @if($radiologyRequest->radiologyTest->preparation_instructions)
                                            <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded-lg">
                                                <p class="text-xs font-semibold text-gray-900 dark:text-white mb-1">
                                                    <i class="fa fa-info-circle text-purple-600 mr-1"></i> Preparation Instructions
                                                </p>
                                                <p class="text-xs text-gray-700 dark:text-gray-300">
                                                    {{ $radiologyRequest->radiologyTest->preparation_instructions }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                            ${{ number_format($radiologyRequest->radiologyTest->price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h3>
                        <div class="space-y-3">
                            <button class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                                <i class="fa fa-print mr-2"></i> Print Request
                            </button>
                            <button class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                <i class="fa fa-file-medical mr-2"></i> Upload Results
                            </button>
                            <button class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                <i class="fa fa-edit mr-2"></i> Edit Request
                            </button>
                            <a href="{{ route('hms.radiology.requests.index') }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition text-center">
                                <i class="fa fa-arrow-left mr-2"></i> Back to List
                            </a>
                        </div>

                        <!-- Patient Quick Info -->
                        @if($radiologyRequest->patient)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Patient Info</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $radiologyRequest->patient->full_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Patient #:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $radiologyRequest->patient->patient_no }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Age:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($radiologyRequest->patient->date_of_birth)->age }}y</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

