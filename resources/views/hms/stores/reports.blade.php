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
                        <span>Reports</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-chart-bar text-purple-600 mr-3"></i>{{ $store->name }} - Reports</h1>
                </div>
                <a href="{{ route('hms.stores.show', $store) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600">Total Stock Value</p>
                    <p class="text-2xl font-bold text-blue-600">${{ number_format($totalValue, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-orange-600">Low Stock Items</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $lowStock->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Expiring Soon</p>
                    <p class="text-2xl font-bold text-red-600">{{ $expiringBatches->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Recent Movements</p>
                    <p class="text-2xl font-bold text-green-600">{{ $recentMovements->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Low Stock Items -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-orange-50 dark:bg-orange-900/20">
                        <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200"><i class="fa fa-exclamation-triangle mr-2"></i>Low Stock Items</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-64 overflow-y-auto">
                        @forelse($lowStock as $item)
                            <div class="px-6 py-3 flex items-center justify-between">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $item->medicine->name ?? 'N/A' }}</span>
                                <span class="text-sm font-semibold text-red-600">{{ $item->quantity }} / {{ $item->minimum_stock }}</span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500 text-sm">All items adequately stocked</div>
                        @endforelse
                    </div>
                </div>

                <!-- Expiring Batches -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200"><i class="fa fa-clock mr-2"></i>Expiring Within 30 Days</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-64 overflow-y-auto">
                        @forelse($expiringBatches as $batch)
                            <div class="px-6 py-3 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $batch->medicine->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Batch: {{ $batch->batch_number }}</p>
                                </div>
                                <span class="text-sm font-semibold text-red-600">{{ $batch->expiry_date?->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500 text-sm">No batches expiring soon</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Movements -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden lg:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fa fa-exchange-alt mr-2"></i>Recent Stock Movements</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Movement #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Direction</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentMovements as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-mono text-blue-600">{{ $movement->movement_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $movement->medicine->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($movement->movement_type) }}</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $movement->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($movement->direction) }}</span></td>
                                    <td class="px-6 py-4 text-sm font-semibold {{ $movement->direction === 'in' ? 'text-green-600' : 'text-red-600' }}">{{ $movement->direction === 'in' ? '+' : '-' }}{{ $movement->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $movement->movement_date?->format('M d, Y') ?? $movement->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No recent movements</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
