<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Inventory Management Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.inventory.stock-report') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                    Stock Report
                </a>
                <a href="{{ route('hms.inventory.purchase-orders.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    New Purchase Order
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Key Inventory Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Total Items</div>
                            <div class="text-3xl font-bold mt-2">{{ number_format($stats['total_items']) }}</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Value: KES {{ number_format($stats['total_value'], 0) }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Low Stock</div>
                            <div class="text-3xl font-bold mt-2">{{ $stats['low_stock'] }}</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Out of Stock: {{ $stats['out_of_stock'] }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Expiring Soon</div>
                            <div class="text-3xl font-bold mt-2">{{ $stats['expiring_soon'] }}</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Expired: {{ $stats['expired'] }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Pending Orders</div>
                            <div class="text-3xl font-bold mt-2">{{ $stats['pending_orders'] }}</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Suppliers: {{ $stats['total_suppliers'] }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('hms.inventory.suppliers.index') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Suppliers</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage suppliers</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.inventory.purchase-orders.index') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Purchase Orders</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View POs</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.inventory.stock-movements.create') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Stock Movement</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Record movement</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.inventory.stock-report') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Reports</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Stock reports</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Alerts & Warnings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Low Stock Items -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">⚠️ Low Stock Alerts</h3>
                            <a href="{{ route('hms.inventory.stock-report') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($lowStockItems as $item)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->medicine_name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $item->category->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-yellow-600">{{ $item->stock_quantity }} units</p>
                                        <p class="text-xs text-gray-500">Min: {{ $item->minimum_stock }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">✅ No low stock items</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Expiring Soon -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">⏰ Expiring Soon (30 days)</h3>
                            <a href="{{ route('hms.inventory.stock-report') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($expiringSoon as $item)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->medicine_name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Stock: {{ $item->stock_quantity }} units</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-orange-600">{{ $item->expiry_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->expiry_date->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">✅ No items expiring soon</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Stock Movements -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Stock Movements</h3>
                        <a href="{{ route('hms.inventory.stock-movements.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Movement #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Medicine</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">User</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentMovements as $movement)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $movement->movement_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $movement->medicine->medicine_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($movement->direction == 'in') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($movement->movement_type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-semibold 
                                            @if($movement->direction == 'in') text-green-600
                                            @else text-red-600
                                            @endif">
                                            {{ $movement->direction == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $movement->movement_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $movement->user->name ?? 'System' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No recent movements</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Info: Pending Orders & Top Suppliers -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pending Purchase Orders -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Purchase Orders</h3>
                            <a href="{{ route('hms.inventory.purchase-orders.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($pendingOrders as $order)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('hms.inventory.purchase-orders.show', $order) }}" class="hover:underline">
                                                {{ $order->po_number }}
                                            </a>
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $order->supplier->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-blue-600">KES {{ number_format($order->total_amount, 0) }}</p>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($order->status == 'draft') bg-gray-100 text-gray-800
                                            @elseif($order->status == 'submitted') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No pending orders</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Suppliers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Suppliers</h3>
                            <a href="{{ route('hms.inventory.suppliers.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($topSuppliers as $supplier)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('hms.inventory.suppliers.show', $supplier) }}" class="hover:underline">
                                                {{ $supplier->name }}
                                            </a>
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $supplier->supplier_type ?? 'General' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-purple-600">{{ $supplier->purchase_orders_count }} orders</p>
                                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($supplier->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No suppliers yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
