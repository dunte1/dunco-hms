<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-clipboard-check text-teal-600 mr-3"></i>Stocktakes
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage physical stock counts and variance tracking</p>
                </div>
                <a href="{{ route('hms.stocktakes.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Stocktake
                </a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Stocktakes</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-yellow-600">In Progress</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-blue-600">Completed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Approved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">All Statuses</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    <select name="store_id" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-search"></i></button>
                    @if(request('status') || request('store_id'))
                        <a href="{{ route('hms.stocktakes.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg text-sm"><i class="fa fa-times mr-1"></i> Clear</a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stocktake #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Performed By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variances</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($stocktakes as $stocktake)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $stocktake->stocktake_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $stocktake->store->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $stocktake->stocktake_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $stocktake->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                               ($stocktake->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                               'bg-green-100 text-green-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $stocktake->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $stocktake->performer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        @if($stocktake->variance_count > 0)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                {{ $stocktake->variance_count }} item(s)
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('hms.stocktakes.show', $stocktake) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs rounded-lg">
                                            <i class="fa fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa fa-clipboard-check text-6xl text-gray-400 mb-4 block"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No stocktakes found</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Start a new stocktake to count physical inventory</p>
                                        <a href="{{ route('hms.stocktakes.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg"><i class="fa fa-plus mr-2"></i> New Stocktake</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($stocktakes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $stocktakes->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
