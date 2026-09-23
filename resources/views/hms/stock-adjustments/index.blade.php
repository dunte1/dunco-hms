<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-sliders-h text-orange-600 mr-3"></i>Stock Adjustments
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track and manage stock level adjustments</p>
                </div>
                <a href="{{ route('hms.stock-adjustments.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Adjustment
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
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Adjustments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-yellow-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Approved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Rejected</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <select name="store_id" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                    <select name="adjustment_type" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">All Types</option>
                        <option value="addition" {{ request('adjustment_type') === 'addition' ? 'selected' : '' }}>Addition</option>
                        <option value="deduction" {{ request('adjustment_type') === 'deduction' ? 'selected' : '' }}>Deduction</option>
                        <option value="correction" {{ request('adjustment_type') === 'correction' ? 'selected' : '' }}>Correction</option>
                        <option value="damage" {{ request('adjustment_type') === 'damage' ? 'selected' : '' }}>Damage</option>
                        <option value="expiry" {{ request('adjustment_type') === 'expiry' ? 'selected' : '' }}>Expiry</option>
                        <option value="stocktake" {{ request('adjustment_type') === 'stocktake' ? 'selected' : '' }}>Stocktake</option>
                    </select>
                    <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-search"></i></button>
                    @if(request('status') || request('store_id') || request('adjustment_type'))
                        <a href="{{ route('hms.stock-adjustments.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg text-sm"><i class="fa fa-times mr-1"></i> Clear</a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Adj #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Adjustment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($adjustments as $adjustment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $adjustment->adjustment_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $adjustment->medicine->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $adjustment->store->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold {{ $adjustment->quantity_adjustment > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $adjustment->quantity_adjustment > 0 ? '+' : '' }}{{ $adjustment->quantity_adjustment }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $adjustment->adjustment_type === 'addition' ? 'bg-green-100 text-green-800' :
                                               ($adjustment->adjustment_type === 'deduction' ? 'bg-red-100 text-red-800' :
                                               ($adjustment->adjustment_type === 'stocktake' ? 'bg-blue-100 text-blue-800' :
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($adjustment->adjustment_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $adjustment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                               ($adjustment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($adjustment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $adjustment->requester->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('hms.stock-adjustments.show', $adjustment) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs rounded-lg">
                                            <i class="fa fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <i class="fa fa-sliders-h text-6xl text-gray-400 mb-4 block"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No stock adjustments found</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create an adjustment to modify stock levels</p>
                                        <a href="{{ route('hms.stock-adjustments.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg"><i class="fa fa-plus mr-2"></i> New Adjustment</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($adjustments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $adjustments->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
