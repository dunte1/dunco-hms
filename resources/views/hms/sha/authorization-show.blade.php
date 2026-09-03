<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-file-invoice text-purple-600 mr-3"></i> Authorization Details
                    </h1>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('hms.sha.authorizations') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $authorization->authorization_number }}</h2>
                        <p class="text-gray-500 mt-1">{{ $authorization->service_type }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $authorization->status_color }}-100 text-{{ $authorization->status_color }}-800">
                        {{ ucfirst($authorization->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Patient</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $authorization->patient->first_name ?? '' }} {{ $authorization->patient->last_name ?? '' }}
                        </p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">SHA Member</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorization->shaMember->sha_member_number ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Service Code</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorization->service_code ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Diagnosis</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorization->diagnosis_code ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Authorized Amount</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorization->authorized_amount ? number_format($authorization->authorized_amount, 2) : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Expiry Date</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorization->expiry_date ? $authorization->expiry_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Authorized Date</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorization->authorized_date ? $authorization->authorized_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>

                @if($authorization->diagnosis_description)
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">Diagnosis</h4>
                        <p class="text-gray-700 dark:text-gray-300">{{ $authorization->diagnosis_description }}</p>
                    </div>
                @endif

                @if($authorization->notes)
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Notes</h4>
                        <p class="text-gray-700 dark:text-gray-300">{{ $authorization->notes }}</p>
                    </div>
                @endif
            </div>

            @if($authorization->api_response)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">API Response</h3>
                    <pre class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-xs overflow-x-auto">{{ json_encode($authorization->api_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
