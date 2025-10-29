<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.packages.index') }}" class="hover:text-emerald-600">Health Packages</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $package->name }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-box-open text-emerald-600 mr-3"></i>
                    Package Details
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Package Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $package->name }}</h3>
                            
                            @if($package->description)
                                <p class="text-gray-700 dark:text-gray-300 mb-6">{{ $package->description }}</p>
                            @endif

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Package Price</p>
                                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($package->price, 2) }}</p>
                                </div>
                                @if($package->duration_days)
                                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Validity</p>
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $package->duration_days }} days</p>
                                </div>
                                @endif
                            </div>

                            @if($package->inclusions)
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                        <i class="fa fa-check-circle text-emerald-600 mr-2"></i> What's Included
                                    </h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $package->inclusions }}</p>
                                </div>
                            @endif

                            @if($package->terms_conditions)
                                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                        <i class="fa fa-info-circle text-yellow-600 mr-2"></i> Terms & Conditions
                                    </h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $package->terms_conditions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Package Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Package Items</h3>
                            <div class="space-y-3">
                                @foreach($package->items as $item)
                                    <div class="flex items-start justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 text-xs rounded-full">
                                                    {{ ucfirst(str_replace('_', ' ', $item->item_type)) }}
                                                </span>
                                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $item->item_name }}</h4>
                                            </div>
                                            @if($item->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span><i class="fa fa-cubes mr-1"></i> Qty: {{ $item->quantity }}</span>
                                                <span><i class="fa fa-tag mr-1"></i> Unit: ${{ number_format($item->unit_price, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                                ${{ number_format($item->total_price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                                <i class="fa fa-shopping-cart mr-2"></i> Sell Package
                            </button>
                            <button class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                <i class="fa fa-print mr-2"></i> Print Details
                            </button>
                            <button class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                                <i class="fa fa-edit mr-2"></i> Edit Package
                            </button>
                            <a href="{{ route('hms.packages.index') }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition text-center">
                                <i class="fa fa-arrow-left mr-2"></i> Back to Packages
                            </a>
                        </div>

                        <!-- Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Package Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Items:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $package->items->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Item Total:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($package->items->sum('total_price'), 2) }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400">Package Price:</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($package->price, 2) }}</span>
                                </div>
                                @if($package->items->sum('total_price') > $package->price)
                                <div class="flex justify-between text-green-600 dark:text-green-400">
                                    <span>Savings:</span>
                                    <span class="font-semibold">${{ number_format($package->items->sum('total_price') - $package->price, 2) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

