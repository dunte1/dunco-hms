<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-pills text-green-600 mr-3"></i>
                        Pharmacy Inventory
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track medicine stock levels, expiry, and consumption</p>
                </div>
                <a href="{{ route('hms.pharmacy.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Pharmacy
                </a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Medicines</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMedicines ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Low Stock</p>
                    <p class="text-2xl font-bold text-red-600">{{ $lowStockCount ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-orange-600">Expiring Soon</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $expiringSoonCount ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">In Stock Value</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($stockValue ?? 0, 2) }}</p>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Batch</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expiry</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($medicines as $medicine)
                                @php
                                    $isLow = $medicine->quantity <= ($medicine->min_stock ?? 10);
                                    $isExpired = $medicine->expiry_date && \Carbon\Carbon::parse($medicine->expiry_date)->isPast();
                                    $isExpiring = $medicine->expiry_date && \Carbon\Carbon::parse($medicine->expiry_date)->diffInDays(now()) <= 30 && !$isExpired;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $isExpired ? 'bg-red-50 dark:bg-red-900/10' : ($isLow ? 'bg-yellow-50 dark:bg-yellow-900/10' : '') }}">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $medicine->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $medicine->medicineCategory?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $medicine->batch_number ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold {{ $isLow ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $medicine->quantity }}</td>
                                    <td class="px-6 py-4 text-sm {{ $isExpired ? 'text-red-600 font-bold' : ($isExpiring ? 'text-orange-600 font-semibold' : 'text-gray-700 dark:text-gray-300') }}">{{ $medicine->expiry_date ? \Carbon\Carbon::parse($medicine->expiry_date)->format('M d, Y') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${{ number_format($medicine->price ?? 0, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @if($isExpired)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                                        @elseif($isExpiring)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Expiring</span>
                                        @elseif($isLow)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa fa-pills text-5xl text-gray-400 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No medicines in inventory</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($medicines) && $medicines instanceof \Illuminate\Pagination\LengthAwarePaginator && $medicines->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $medicines->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
