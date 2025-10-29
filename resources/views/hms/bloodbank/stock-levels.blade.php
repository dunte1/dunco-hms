<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-tint text-red-600 mr-3"></i>
                    Blood Stock Levels
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor available blood inventory by type</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Donors</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_donors']) }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Active Donors</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['active_donors']) }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Blood Types</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['blood_types'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-layer-group text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blood Group Stock Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($bloodGroups as $group)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6">
                            <div class="flex items-center justify-between text-white">
                                <div>
                                    <h3 class="text-3xl font-bold">{{ $group->name }}</h3>
                                    <p class="text-sm opacity-90 mt-1">Blood Group</p>
                                </div>
                                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                    <i class="fa fa-tint text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Available Donors</span>
                                    <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $group->donors_count ?? 0 }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ min(100, ($group->donors_count ?? 0) * 10) }}%"></div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex justify-between">
                                    <span>Compatibility:</span>
                                    <span class="font-semibold">{{ $group->can_donate_to ?? 'Universal' }}</span>
                                </div>
                                @if($group->donors_count > 0)
                                    <span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-xs font-semibold">
                                        <i class="fa fa-check-circle mr-1"></i> Available
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded text-xs font-semibold">
                                        <i class="fa fa-exclamation-circle mr-1"></i> Low Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($bloodGroups->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                    <i class="fa fa-tint text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Blood Groups Available</h3>
                    <p class="text-gray-600 dark:text-gray-400">Add blood groups to start tracking inventory</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

