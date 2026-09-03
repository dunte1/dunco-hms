<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-shield text-green-600 mr-3"></i> SHA Member Details
                    </h1>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('hms.sha.members') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">
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
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $member->full_name }}</h2>
                        <p class="text-green-600 font-medium mt-1">SHA #{{ $member->sha_member_number }}</p>
                    </div>
                    <div>
                        @if($member->isEligible())
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">Eligible</span>
                        @else
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">National ID</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->national_id ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Date of Birth</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Gender</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($member->gender ?? 'N/A') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Phone</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Tier Level</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->tier_level ? ucfirst(str_replace('_', ' ', $member->tier_level)) : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Employer</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->employer_name ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Contribution Status</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($member->contribution_status ?? 'N/A') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Remaining Benefits</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->remaining_benefits ? number_format($member->remaining_benefits, 2) : 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-xs text-gray-500 uppercase">Last Verified</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->last_verified_at ? $member->last_verified_at->diffForHumans() : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Authorizations -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Authorizations ({{ $member->authorizations->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($member->authorizations as $auth)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $auth->authorization_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->service_type }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->authorized_amount ? number_format($auth->authorized_amount, 2) : 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $auth->status_color }}-100 text-{{ $auth->status_color }}-800">{{ ucfirst($auth->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->expiry_date ? $auth->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No authorizations yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
