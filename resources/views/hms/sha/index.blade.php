<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-shield-heart text-green-600 mr-3"></i>
                        SHA / SHIF Dashboard
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Social Health Authority integration</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['members'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Members</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['verified'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Active</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['authorizations'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Authorizations</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Approved</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-3xl font-bold text-teal-600">{{ $stats['providers_configured'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Providers</p>
                </div>
            </div>

            <!-- Member Verification -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fa fa-search text-green-600 mr-2"></i> Verify SHA Member
                    </h3>
                    <form action="{{ route('hms.sha.verify') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member Number</label>
                            <input type="text" name="member_number" required placeholder="e.g. 9547382xxx"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                            @error('member_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                            <i class="fa fa-search mr-2"></i> Verify
                        </button>
                    </form>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fa fa-user-plus text-blue-600 mr-2"></i> Register SHA Member
                    </h3>
                    <form action="{{ route('hms.sha.member.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient</label>
                            <select name="patient_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select patient...</option>
                                @foreach(\App\Models\Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']) as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SHA Member Number</label>
                            <input type="text" name="sha_member_number" required placeholder="e.g. 9547382xxx"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">National ID</label>
                                <input type="text" name="national_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Eligibility</label>
                                <select name="eligibility_status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            <i class="fa fa-save mr-2"></i> Register Member
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Members -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Members</h3>
                    <a href="{{ route('hms.sha.members') }}" class="text-sm text-green-600 hover:text-green-700">View all <i class="fa fa-arrow-right ml-1"></i></a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SHA Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($members as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $member->full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $member->sha_member_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->isEligible())
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Eligible</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('hms.sha.member.show', $member) }}" class="text-green-600 hover:text-green-700">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No members registered</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('hms.sha.authorizations') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow p-4 flex items-center justify-between">
                    <div><p class="text-lg font-bold">Authorizations</p><p class="text-sm opacity-75">Manage & view</p></div>
                    <i class="fa fa-file-signature text-2xl"></i>
                </a>
                <a href="{{ route('hms.sha.members') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-xl shadow p-4 flex items-center justify-between">
                    <div><p class="text-lg font-bold">Members</p><p class="text-sm opacity-75">List all</p></div>
                    <i class="fa fa-users text-2xl"></i>
                </a>
                <a href="{{ route('hms.sha.providers') }}" class="bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow p-4 flex items-center justify-between">
                    <div><p class="text-lg font-bold">Providers</p><p class="text-sm opacity-75">Configure</p></div>
                    <i class="fa fa-hospital text-2xl"></i>
                </a>
                <a href="{{ route('hms.sha.service-codes') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow p-4 flex items-center justify-between">
                    <div><p class="text-lg font-bold">Service Codes</p><p class="text-sm opacity-75">Tariffs</p></div>
                    <i class="fa fa-list-alt text-2xl"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
