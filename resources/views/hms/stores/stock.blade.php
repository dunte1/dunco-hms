<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.stores.index') }}" class="hover:text-blue-600">Stores</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <a href="{{ route('hms.stores.show', $store) }}" class="hover:text-blue-600">{{ $store->name }}</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Stock</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-boxes text-blue-600 mr-3"></i>{{ $store->name }} - Stock</h1>
                </div>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('adjustModal').classList.remove('hidden')" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-sliders-h mr-1"></i> Adjust Stock</button>
                    <a href="{{ route('hms.stores.show', $store) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
                <form method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medicine..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    <select name="filter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">All Items</option>
                        <option value="low" {{ request('filter') === 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ request('filter') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                    <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($stockItems as $item)
                                @php
                                    $isLow = $item->quantity <= $item->minimum_stock;
                                    $isOut = $item->quantity <= 0;
                                    $value = $item->quantity * $item->average_cost;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $isOut ? 'bg-red-50 dark:bg-red-900/10' : ($isLow ? 'bg-yellow-50 dark:bg-yellow-900/10' : '') }}">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->medicine->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->medicine->medicineCategory->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold {{ $isLow ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->minimum_stock }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->maximum_stock }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">${{ number_format($item->average_cost, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">${{ number_format($value, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $isOut ? 'bg-red-100 text-red-800' : ($isLow ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-6 py-12 text-center text-gray-500">No stock items in this store</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($stockItems->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $stockItems->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Adjust Stock Modal -->
    <div id="adjustModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-sliders-h text-amber-600 mr-2"></i>Adjust Stock</h3>
            <form action="{{ route('hms.stores.adjust-stock', $store) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medicine *</label>
                    <select name="medicine_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select Medicine</option>
                        @foreach(\App\Models\Medicine::orderBy('name')->get() as $med)
                            <option value="{{ $med->id }}">{{ $med->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adjustment (+ for add, - for remove) *</label>
                    <input type="number" name="adjustment" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="+50 or -10">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Batch Number</label>
                    <input type="text" name="batch_number" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason *</label>
                    <textarea name="reason" rows="2" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Reason for adjustment..."></textarea>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold">Apply Adjustment</button>
                    <button type="button" onclick="document.getElementById('adjustModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
