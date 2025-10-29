<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-ambulance text-red-600 mr-3"></i>
                        Ambulance Fleet Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage ambulance vehicles and their status</p>
                </div>
                <a href="{{ route('hms.ambulance.create-ambulance') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Ambulance
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Fleet</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-ambulance text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Available</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['available'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">In Use</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['in_use'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-spinner text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs opacity-90">Basic</p>
                            <p class="text-2xl font-bold mt-1">{{ $stats['basic'] }}</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-truck-medical text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs opacity-90">Critical Care</p>
                            <p class="text-2xl font-bold mt-1">{{ $stats['critical_care'] }}</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-heartbeat text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.ambulance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by vehicle number, driver..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-filter mr-1"></i> Vehicle Type
                        </label>
                        <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                            <option value="">All Types</option>
                            <option value="basic" {{ request('type') == 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="advanced" {{ request('type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            <option value="critical_care" {{ request('type') == 'critical_care' ? 'selected' : '' }}>Critical Care</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-info-circle mr-1"></i> Status
                        </label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2 md:col-span-4">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.ambulance.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Ambulances Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($ambulances as $ambulance)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                        <i class="fa fa-ambulance text-2xl text-red-600 dark:text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $ambulance->vehicle_number }}</h3>
                                        <span class="text-xs px-2 py-1 rounded-full inline-block mt-1
                                            {{ $ambulance->vehicle_type == 'basic' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                               ($ambulance->vehicle_type == 'advanced' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 
                                               'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            {{ ucfirst(str_replace('_', ' ', $ambulance->vehicle_type)) }}
                                        </span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $ambulance->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ $ambulance->is_available ? 'Available' : 'In Use' }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-user text-gray-400 mr-2 w-5"></i>
                                    <span class="font-medium">{{ $ambulance->driver_name }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-phone text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $ambulance->driver_phone }}</span>
                                </div>
                                @if($ambulance->equipment_list)
                                <div class="flex items-start text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-box text-gray-400 mr-2 w-5 mt-0.5"></i>
                                    <span class="line-clamp-2">{{ $ambulance->equipment_list }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-eye mr-1"></i> View
                                </button>
                                <button class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-edit mr-1"></i> Edit
                                </button>
                                <button class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-ambulance text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No ambulances found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Start by adding a new ambulance vehicle</p>
                        <a href="{{ route('hms.ambulance.create-ambulance') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add First Ambulance
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($ambulances->hasPages())
                <div class="mt-6">
                    {{ $ambulances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
