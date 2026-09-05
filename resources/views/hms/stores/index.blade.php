<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-store text-indigo-600 mr-3"></i>Store Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage pharmacy stores, sub-stores, warehouses, and stock locations</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.stores.transfer') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-exchange-alt mr-2"></i> Transfer Stock
                    </a>
                    <a href="{{ route('hms.stores.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-plus mr-2"></i> Add Store
                    </a>
                </div>
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
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Stores</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_stores'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active_stores'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-blue-600">Total Stock Value</p>
                    <p class="text-2xl font-bold text-blue-600">${{ number_format($stats['total_stock_value'], 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Low Stock Items</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['low_stock_items'] }}</p>
                </div>
            </div>

            <!-- Store Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($stores as $store)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $store->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $store->code }} | {{ ucfirst($store->type) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($store->is_main)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Main</span>
                                    @endif
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $store->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($store->status) }}</span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-boxes text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $store->stock_items_count }} items in stock</span>
                                </div>
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-layer-group text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $store->batches_count }} active batches</span>
                                </div>
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-shopping-cart text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $store->purchase_orders_count }} purchase orders</span>
                                </div>
                                @if($store->manager)
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <i class="fa fa-user text-gray-400 mr-2 w-5"></i>
                                        <span>{{ $store->manager->name }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('hms.stores.show', $store) }}" class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg text-center transition">
                                    <i class="fa fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('hms.stores.stock', $store) }}" class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg text-center transition">
                                    <i class="fa fa-boxes mr-1"></i> Stock
                                </a>
                                <a href="{{ route('hms.stores.batches', $store) }}" class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg text-center transition">
                                    <i class="fa fa-layer-group mr-1"></i> Batches
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-store text-6xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No stores configured</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add your first store to start managing inventory</p>
                        <a href="{{ route('hms.stores.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg"><i class="fa fa-plus mr-2"></i> Add First Store</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
