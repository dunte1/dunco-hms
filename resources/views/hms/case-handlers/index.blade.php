<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-nurse text-purple-600 mr-3"></i>
                        Case Handlers
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage social workers and case handlers</p>
                </div>
                <a href="{{ route('hms.case-handlers.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-user-plus mr-2"></i> Add Case Handler
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Handlers</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Active</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['active'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Inactive</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['inactive'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-slash text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.case-handlers.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name, ID, email, specialization..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.case-handlers.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Case Handlers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($caseHandlers as $handler)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                        {{ substr($handler->first_name, 0, 1) }}{{ substr($handler->last_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $handler->first_name }} {{ $handler->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $handler->handler_id }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $handler->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $handler->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-briefcase text-gray-400 mr-2 w-5"></i>
                                    <span class="font-medium">{{ $handler->specialization }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-envelope text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $handler->email }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-phone text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $handler->phone }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-graduation-cap text-gray-400 mr-2 w-5"></i>
                                    <span class="line-clamp-1">{{ $handler->qualifications }}</span>
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
                        <i class="fa fa-user-nurse text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No case handlers found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add a new case handler to get started</p>
                        <a href="{{ route('hms.case-handlers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                            <i class="fa fa-user-plus mr-2"></i> Add First Case Handler
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($caseHandlers->hasPages())
                <div class="mt-6">
                    {{ $caseHandlers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

