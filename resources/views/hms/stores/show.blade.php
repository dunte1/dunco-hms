<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.stores.index') }}" class="hover:text-blue-600">Stores</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $store->name }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-store text-indigo-600 mr-3"></i>{{ $store->name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.stores.stock', $store) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"><i class="fa fa-boxes mr-1"></i> Stock</a>
                    <a href="{{ route('hms.stores.batches', $store) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-layer-group mr-1"></i> Batches</a>
                    <a href="{{ route('hms.stores.reports', $store) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg"><i class="fa fa-chart-bar mr-1"></i> Reports</a>
                    <a href="{{ route('hms.stores.edit', $store) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.stores.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <!-- Store Info Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600">Total Stock Value</p>
                    <p class="text-2xl font-bold text-blue-600">${{ number_format($totalValue, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Items In Stock</p>
                    <p class="text-2xl font-bold text-green-600">{{ $store->stockItems()->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-orange-600">Low Stock</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $lowStockCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-600">{{ $outOfStockCount }}</p>
                </div>
            </div>

            <!-- Store Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Store Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Code:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $store->code }}</span></div>
                            <div><span class="text-gray-500">Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($store->type) }}</span></div>
                            <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $store->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($store->status) }}</span></div>
                            <div><span class="text-gray-500">Main Store:</span> <span class="font-medium {{ $store->is_main ? 'text-yellow-600' : 'text-gray-900 dark:text-white' }}">{{ $store->is_main ? 'Yes' : 'No' }}</span></div>
                            @if($store->manager)<div><span class="text-gray-500">Manager:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $store->manager->name }}</span></div>@endif
                            @if($store->phone)<div><span class="text-gray-500">Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $store->phone }}</span></div>@endif
                            @if($store->address)<div class="md:col-span-2"><span class="text-gray-500">Address:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $store->address }}</span></div>@endif
                        </div>
                    </div>

                    <!-- Top Stock Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stock Items (Top 10 Low)</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($stockItems->take(10) as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $item->medicine->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold {{ $item->quantity <= $item->minimum_stock ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $item->minimum_stock }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->quantity <= 0 ? 'bg-red-100 text-red-800' : ($item->quantity <= $item->minimum_stock ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ $item->quantity <= 0 ? 'Out' : ($item->quantity <= $item->minimum_stock ? 'Low' : 'OK') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No stock items yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('hms.stores.stock', $store) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg text-sm"><i class="fa fa-boxes mr-2"></i>View Stock</a>
                            <a href="{{ route('hms.stores.batches', $store) }}" class="block w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-center rounded-lg text-sm"><i class="fa fa-layer-group mr-2"></i>Manage Batches</a>
                            <a href="{{ route('hms.stores.reports', $store) }}" class="block w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-center rounded-lg text-sm"><i class="fa fa-chart-bar mr-2"></i>Store Reports</a>
                            <a href="{{ route('hms.stores.transfer') }}" class="block w-full px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-center rounded-lg text-sm"><i class="fa fa-exchange-alt mr-2"></i>Transfer Stock</a>
                        </div>
                    </div>

                    @if($expiringCount > 0)
                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-xl p-4">
                            <h3 class="text-sm font-semibold text-orange-800 dark:text-orange-200 mb-2"><i class="fa fa-clock mr-1"></i> Expiring Soon</h3>
                            <p class="text-sm text-orange-700 dark:text-orange-300">{{ $expiringCount }} batches expiring within 30 days</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
