<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-hand-holding-heart text-rose-600 mr-3"></i>
                        Blood Donors
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage blood donor registrations</p>
                </div>
                <a href="{{ route('hms.bloodbank.donors.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-user-plus mr-2"></i> Register Donor
                </a>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Donors List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($donors as $donor)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-rose-500 to-rose-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-br from-rose-400 to-rose-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                        {{ substr($donor->first_name, 0, 1) }}{{ substr($donor->last_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $donor->first_name }} {{ $donor->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $donor->donor_id }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-tint text-rose-600 mr-2 w-5"></i>
                                    <span class="font-semibold text-rose-600">{{ $donor->bloodGroup->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-phone text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $donor->phone }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-envelope text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $donor->email }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-venus-mars text-gray-400 mr-2 w-5"></i>
                                    <span>{{ ucfirst($donor->gender) }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-birthday-cake text-gray-400 mr-2 w-5"></i>
                                    <span>{{ \Carbon\Carbon::parse($donor->date_of_birth)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($donor->date_of_birth)->age }}y)</span>
                                </div>
                            </div>

                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-eye mr-1"></i> View
                                </button>
                                <button class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-edit mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-hand-holding-heart text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No blood donors found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Register a new donor to get started</p>
                        <a href="{{ route('hms.bloodbank.donors.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg">
                            <i class="fa fa-user-plus mr-2"></i> Register First Donor
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($donors->hasPages())
                <div class="mt-6">
                    {{ $donors->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

