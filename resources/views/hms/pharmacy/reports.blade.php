<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-chart-bar text-green-600 mr-3"></i>
                        Pharmacy Reports
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pharmacy consumption, stock, and sales analytics</p>
                </div>
                <a href="{{ route('hms.pharmacy.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Pharmacy
                </a>
            </div>

            <!-- Report Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Top Dispensed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><i class="fa fa-fire text-orange-500 mr-2"></i>Top Dispensed</h3>
                    <div class="space-y-3">
                        @forelse($topDispensed ?? [] as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ $item->name ?? $item->medicine->name ?? 'N/A' }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->total_dispensed ?? $item->total ?? 0 }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">No dispensing data yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><i class="fa fa-exclamation-triangle text-yellow-500 mr-2"></i>Low Stock Alerts</h3>
                    <div class="space-y-3">
                        @forelse($lowStockMedicines ?? [] as $medicine)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ $medicine->name }}</span>
                                <span class="font-semibold text-red-600">{{ $medicine->quantity }} left</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">All medicines adequately stocked</p>
                        @endforelse
                    </div>
                </div>

                <!-- Expiry Alerts -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><i class="fa fa-clock text-red-500 mr-2"></i>Expiry Alerts</h3>
                    <div class="space-y-3">
                        @forelse($expiringMedicines ?? [] as $medicine)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ $medicine->name }}</span>
                                <span class="font-semibold text-orange-600">{{ \Carbon\Carbon::parse($medicine->expiry_date)->format('M d, Y') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">No medicines expiring soon</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Monthly Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><i class="fa fa-calendar text-green-600 mr-2"></i>Monthly Summary</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($monthlyRevenue ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Revenue</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDispensed ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Items Dispensed</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($monthlyPurchaseCost ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Purchase Cost</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPrescriptions ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Prescriptions Filled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
