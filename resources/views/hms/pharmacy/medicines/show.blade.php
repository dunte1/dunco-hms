<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.pharmacy.medicines.index') }}" class="hover:text-green-600">Medicines</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $medicine->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-pills text-green-600 mr-3"></i>
                            {{ $medicine->name }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $medicine->generic_name ?? 'No generic name' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.pharmacy.medicines.edit', $medicine) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        <a href="{{ route('hms.pharmacy.medicines.index') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Medicine Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-info-circle text-green-600 mr-2"></i>
                                Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicine Name</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Generic Name</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->generic_name ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            {{ $medicine->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manufacturer</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->manufacturer ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage Form</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white capitalize">{{ $medicine->dosage_form ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Strength</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->strength ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            @if($medicine->description)
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $medicine->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-warehouse text-blue-600 mr-2"></i>
                                Stock Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                        <i class="fa fa-boxes text-blue-600 text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Stock</p>
                                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $medicine->stock_quantity }}</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                                        <i class="fa fa-exclamation-triangle text-yellow-600 text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Stock</p>
                                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $medicine->minimum_stock }}</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                        <i class="fa fa-dollar-sign text-green-600 text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price</p>
                                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($medicine->unit_price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stock Status -->
                            <div class="mt-6">
                                @php
                                    $isLowStock = $medicine->stock_quantity <= $medicine->minimum_stock;
                                    $isOutOfStock = $medicine->stock_quantity == 0;
                                @endphp
                                <div class="flex items-center justify-center">
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                                        {{ $isOutOfStock ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                           ($isLowStock ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                           'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                        @if($isOutOfStock)
                                            <i class="fa fa-times-circle mr-2"></i>Out of Stock
                                        @elseif($isLowStock)
                                            <i class="fa fa-exclamation-triangle mr-2"></i>Low Stock
                                        @else
                                            <i class="fa fa-check-circle mr-2"></i>In Stock
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Information -->
                    @if($medicine->expiry_date)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa fa-calendar-alt text-purple-600 mr-2"></i>
                                    Expiry Information
                                </h3>
                                <div class="text-center">
                                    @php
                                        $daysUntilExpiry = now()->diffInDays($medicine->expiry_date, false);
                                        $isExpired = $medicine->expiry_date < now();
                                        $isExpiringSoon = $daysUntilExpiry <= 30 && $daysUntilExpiry > 0;
                                    @endphp
                                    <div class="p-4 rounded-lg
                                        {{ $isExpired ? 'bg-red-50 dark:bg-red-900' : 
                                           ($isExpiringSoon ? 'bg-yellow-50 dark:bg-yellow-900' : 
                                           'bg-green-50 dark:bg-green-900') }}">
                                        <i class="fa fa-calendar text-3xl mb-2
                                            {{ $isExpired ? 'text-red-600' : 
                                               ($isExpiringSoon ? 'text-yellow-600' : 
                                               'text-green-600') }}"></i>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</p>
                                        <p class="text-xl font-bold
                                            {{ $isExpired ? 'text-red-600 dark:text-red-400' : 
                                               ($isExpiringSoon ? 'text-yellow-600 dark:text-yellow-400' : 
                                               'text-green-600 dark:text-green-400') }}">
                                            {{ $medicine->expiry_date->format('M d, Y') }}
                                        </p>
                                        @if($isExpired)
                                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">Expired {{ abs($daysUntilExpiry) }} days ago</p>
                                        @elseif($isExpiringSoon)
                                            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">Expires in {{ $daysUntilExpiry }} days</p>
                                        @else
                                            <p class="text-sm text-green-600 dark:text-green-400 mt-1">Expires in {{ $daysUntilExpiry }} days</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('hms.pharmacy.medicines.edit', $medicine) }}" 
                                   class="w-full flex items-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                                    <i class="fa fa-edit text-blue-600 mr-3"></i>
                                    <span class="text-sm font-medium text-blue-900 dark:text-blue-200">Edit Medicine</span>
                                </a>
                                <button onclick="alert('Stock adjustment feature coming soon!')" 
                                        class="w-full flex items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                                    <i class="fa fa-plus text-green-600 mr-3"></i>
                                    <span class="text-sm font-medium text-green-900 dark:text-green-200">Adjust Stock</span>
                                </button>
                                <button onclick="alert('Prescription history feature coming soon!')" 
                                        class="w-full flex items-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors">
                                    <i class="fa fa-prescription text-purple-600 mr-3"></i>
                                    <span class="text-sm font-medium text-purple-900 dark:text-purple-200">Prescription History</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Medicine Statistics -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Value</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($medicine->stock_quantity * $medicine->unit_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Stock Level</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ round(($medicine->stock_quantity / max($medicine->minimum_stock * 3, 1)) * 100) }}%
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Days Since Added</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $medicine->created_at->diffInDays() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Created:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $medicine->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $medicine->updated_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Medicine ID:</span>
                                    <span class="text-gray-900 dark:text-white font-mono">{{ $medicine->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>