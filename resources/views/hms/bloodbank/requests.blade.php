<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-file-medical text-red-600 mr-3"></i>
                        Blood Requests
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage patient blood requests</p>
                </div>
                <a href="{{ route('hms.bloodbank.requests.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Request
                </a>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Requests List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($requests as $request)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $request->request_number }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $request->status == 'fulfilled' ? 'bg-green-100 text-green-800' : 
                                               ($request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($request->status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                        <div><i class="fa fa-user mr-1 text-gray-400"></i> Patient: {{ $request->patient->full_name ?? 'N/A' }}</div>
                                        <div><i class="fa fa-user-md mr-1 text-gray-400"></i> Doctor: Dr. {{ $request->doctor->first_name ?? 'N/A' }} {{ $request->doctor->last_name ?? '' }}</div>
                                        <div><i class="fa fa-tint mr-1 text-red-600"></i> Blood Group: <span class="font-semibold text-red-600">{{ $request->bloodGroup->name ?? 'N/A' }}</span></div>
                                        <div><i class="fa fa-flask mr-1 text-gray-400"></i> Units: <span class="font-semibold">{{ $request->units_required }}</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                    <i class="fa fa-clipboard-list text-red-600 mr-2"></i> Reason for Request
                                </h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $request->reason }}</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fa fa-calendar mr-1"></i> Requested: {{ $request->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                        <i class="fa fa-eye mr-1"></i> View
                                    </button>
                                    <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg">
                                        <i class="fa fa-check mr-1"></i> Fulfill
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-file-medical text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No blood requests found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create a new request to get started</p>
                        <a href="{{ route('hms.bloodbank.requests.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Create First Request
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($requests->hasPages())
                <div class="mt-6">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

