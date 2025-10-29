<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-folder-open text-amber-600 mr-3"></i>
                        Patient Cases
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage patient case assignments</p>
                </div>
                <a href="{{ route('hms.case-handlers.cases.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Case
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Cases</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-folder text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Open Cases</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['open'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-folder-open text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">In Progress</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['in_progress'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-spinner text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Urgent</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['urgent'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-exclamation-triangle text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.case-handlers.cases') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by case number, patient, description..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-filter mr-1"></i> Case Type
                        </label>
                        <select name="case_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Types</option>
                            <option value="medical" {{ request('case_type') == 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="social" {{ request('case_type') == 'social' ? 'selected' : '' }}>Social</option>
                            <option value="financial" {{ request('case_type') == 'financial' ? 'selected' : '' }}>Financial</option>
                            <option value="legal" {{ request('case_type') == 'legal' ? 'selected' : '' }}>Legal</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-exclamation-circle mr-1"></i> Priority
                        </label>
                        <select name="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-info-circle mr-1"></i> Status
                        </label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2 md:col-span-5">
                        <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.case-handlers.cases') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Cases List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($cases as $case)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $case->case_number }}
                                        </h3>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $case->priority == 'urgent' ? 'bg-red-100 text-red-800' : 
                                               ($case->priority == 'high' ? 'bg-orange-100 text-orange-800' : 
                                               ($case->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-green-100 text-green-800')) }}">
                                            {{ ucfirst($case->priority) }} Priority
                                        </span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $case->case_type)) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $case->status == 'open' ? 'bg-blue-100 text-blue-800' : 
                                               ($case->status == 'in_progress' ? 'bg-purple-100 text-purple-800' : 
                                               ($case->status == 'resolved' ? 'bg-green-100 text-green-800' : 
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $case->status ?? 'open')) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span><i class="fa fa-user mr-1"></i> {{ $case->patient->full_name ?? 'N/A' }}</span>
                                        <span><i class="fa fa-user-nurse mr-1"></i> {{ $case->caseHandler ? $case->caseHandler->first_name . ' ' . $case->caseHandler->last_name : 'Unassigned' }}</span>
                                        <span><i class="fa fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($case->opened_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $case->description }}</p>
                                @if($case->notes)
                                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                                        <p class="text-xs text-gray-500 dark:text-gray-400"><strong>Notes:</strong> {{ $case->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-folder-open text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No cases found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create a new case to get started</p>
                        <a href="{{ route('hms.case-handlers.cases.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Create First Case
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($cases->hasPages())
                <div class="mt-6">
                    {{ $cases->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

