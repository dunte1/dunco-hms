<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stock Report') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Alert Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-red-50 dark:bg-red-900 border-l-4 border-red-500 p-4">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">Low Stock Alert</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $lowStock->count() }}</p>
                    <p class="text-sm text-red-700 dark:text-red-300">Medicines below minimum stock</p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900 border-l-4 border-orange-500 p-4">
                    <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200">Expiring Soon</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ $expiringMedicines->count() }}</p>
                    <p class="text-sm text-orange-700 dark:text-orange-300">Within 3 months</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 border-l-4 border-gray-500 p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Expired</h3>
                    <p class="text-3xl font-bold text-gray-600">{{ $expiredMedicines->count() }}</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">Already expired</p>
                </div>
            </div>

            <!-- Low Stock Medicines -->
            @if($lowStock->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🔴 Low Stock Medicines</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Medicine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Current Stock</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Minimum</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($lowStock as $medicine)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $medicine->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $medicine->category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-red-600">{{ $medicine->stock_quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">{{ $medicine->minimum_stock }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Low Stock
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Expiring Medicines -->
            @if($expiringMedicines->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">⚠️ Medicines Expiring Soon</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Medicine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Expiry Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Days Left</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($expiringMedicines as $medicine)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $medicine->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $medicine->category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">{{ $medicine->stock_quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-orange-600">{{ $medicine->expiry_date?->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            {{ $medicine->expiry_date?->diffInDays(now()) }} days
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- All Stock -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📊 All Stock Levels</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Medicine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Current Stock</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Min Stock</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Unit Price</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($medicines as $medicine)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $medicine->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $medicine->category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-right font-medium 
                                        {{ $medicine->stock_quantity <= $medicine->minimum_stock ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                        {{ $medicine->stock_quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">{{ $medicine->minimum_stock }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">{{ number_format($medicine->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($medicine->stock_quantity <= $medicine->minimum_stock)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Low</span>
                                        @elseif($medicine->stock_quantity <= ($medicine->minimum_stock * 2))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Good</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

