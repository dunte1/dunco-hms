<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-md text-red-600 mr-3"></i>
                        Operation Theatre Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Surgical procedures and operation reports</p>
                </div>
                <a href="{{ route('hms.operations.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Operation Report
                </a>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Operations List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($operations as $operation)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $operation->operation_name }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $operation->outcome == 'successful' ? 'bg-green-100 text-green-800' : 
                                               ($operation->outcome == 'complications' ? 'bg-orange-100 text-orange-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($operation->outcome) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <div><i class="fa fa-hashtag mr-1 text-gray-400"></i> {{ $operation->report_number }}</div>
                                        <div><i class="fa fa-user mr-1 text-gray-400"></i> Patient: {{ $operation->patient->first_name ?? 'N/A' }} {{ $operation->patient->last_name ?? '' }}</div>
                                        <div><i class="fa fa-calendar mr-1 text-gray-400"></i> {{ \Carbon\Carbon::parse($operation->operation_date)->format('M d, Y') }}</div>
                                        <div><i class="fa fa-clock mr-1 text-gray-400"></i> {{ $operation->start_time }} - {{ $operation->end_time }} ({{ $operation->duration_minutes }} min)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Operation Team -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">
                                    <i class="fa fa-users text-red-600 mr-2"></i> Operation Team
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Surgeon</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $operation->surgeon ? $operation->surgeon->first_name . ' ' . $operation->surgeon->last_name : 'N/A' }}
                                        </p>
                                    </div>
                                    @if($operation->assistant_doctor_id)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Assistant Doctor</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $operation->assistantDoctor->first_name }} {{ $operation->assistantDoctor->last_name }}
                                        </p>
                                    </div>
                                    @endif
                                    @if($operation->anesthesiologist_id)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Anesthesiologist</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $operation->anesthesiologist->first_name }} {{ $operation->anesthesiologist->last_name }}
                                        </p>
                                    </div>
                                    @endif
                                    @if($operation->nurse_id)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Nurse</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $operation->nurse->first_name }} {{ $operation->nurse->last_name }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Operation Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Description</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $operation->operation_description }}</p>
                                </div>
                                @if($operation->anesthesia_type)
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Anesthesia Type</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $operation->anesthesia_type }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                    <i class="fa fa-eye mr-1"></i> View Full Report
                                </button>
                                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg">
                                    <i class="fa fa-print mr-1"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-user-md text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No operation reports found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create a new operation report to get started</p>
                        <a href="{{ route('hms.operations.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Create First Report
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($operations->hasPages())
                <div class="mt-6">
                    {{ $operations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

