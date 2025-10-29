<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-building text-blue-600 mr-3"></i>
                        {{ $provider->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Provider Details</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.insurance.providers.edit', $provider) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.insurance.providers.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                            <h3 class="text-lg font-bold text-white">Basic Information</h3>
                        </div>
                        <div class="p-6 grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Provider Name</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $provider->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Provider Code</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $provider->code }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $provider->contact_person ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                <p class="mt-1">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $provider->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $provider->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                            <h3 class="text-lg font-bold text-white">Contact Information</h3>
                        </div>
                        <div class="p-6 grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $provider->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $provider->phone }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</label>
                                <p class="mt-1 text-blue-600 dark:text-blue-400">
                                    @if($provider->website)
                                        <a href="{{ $provider->website }}" target="_blank" class="hover:underline">{{ $provider->website }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $provider->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Coverage & Payment Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                            <h3 class="text-lg font-bold text-white">Coverage & Payment Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Coverage Limit</label>
                                <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                                    @if($provider->coverage_limit)
                                        ${{ number_format($provider->coverage_limit, 0) }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Copayment</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    @if($provider->copayment_percentage)
                                        {{ $provider->copayment_percentage }}%
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Deductible</label>
                                <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">
                                    @if($provider->deductible_amount)
                                        ${{ number_format($provider->deductible_amount, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- API & Integration -->
                    @if($provider->api_endpoint || $provider->claim_submission_url)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4">
                            <h3 class="text-lg font-bold text-white">API & Integration</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($provider->claim_submission_url)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Claim Submission URL</label>
                                <p class="mt-1 text-sm text-blue-600 dark:text-blue-400 break-all">{{ $provider->claim_submission_url }}</p>
                            </div>
                            @endif
                            @if($provider->api_endpoint)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">API Endpoint</label>
                                <p class="mt-1 text-sm text-blue-600 dark:text-blue-400 break-all">{{ $provider->api_endpoint }}</p>
                            </div>
                            @endif
                            @if($provider->api_key)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">API Key</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ str_repeat('*', 20) }}{{ substr($provider->api_key, -4) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($provider->notes)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
                            <h3 class="text-lg font-bold text-white">Notes</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-900 dark:text-white">{{ $provider->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Statistics Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-4">Quick Stats</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm opacity-90">Total Patients</span>
                                <span class="text-2xl font-bold">{{ $stats['total_patients'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm opacity-90">Active Policies</span>
                                <span class="text-2xl font-bold">{{ $stats['active_policies'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm opacity-90">Total Claims</span>
                                <span class="text-2xl font-bold">{{ $stats['total_claims'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm opacity-90">Pending Claims</span>
                                <span class="text-2xl font-bold">{{ $stats['pending_claims'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('hms.insurance.claims.create', ['provider' => $provider->id]) }}" class="flex items-center px-4 py-3 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 rounded-lg transition">
                                <i class="fa fa-plus-circle text-blue-600 dark:text-blue-400 mr-3"></i>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">New Claim</span>
                            </a>
                            <a href="{{ route('hms.insurance.claims.index', ['provider' => $provider->id]) }}" class="flex items-center px-4 py-3 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 rounded-lg transition">
                                <i class="fa fa-list text-green-600 dark:text-green-400 mr-3"></i>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">View All Claims</span>
                            </a>
                            <form action="{{ route('hms.insurance.providers.verify', $provider) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-3 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 rounded-lg transition">
                                    <i class="fa fa-check-circle text-purple-600 dark:text-purple-400 mr-3"></i>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Verify Provider</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Timestamps</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Created:</span>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $provider->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Last Updated:</span>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $provider->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>







