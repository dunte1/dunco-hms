<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-hospital text-teal-600 mr-3"></i>
                        Assign Nurses to Wards
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage ward assignments for nursing staff</p>
                </div>
                <a href="{{ route('hms.nurses.index') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-users mr-2"></i> All Nurses
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Nurses</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_nurses'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-nurse text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Assigned</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['assigned'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Unassigned</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['unassigned'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-clock text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Departments</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['departments'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-building text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.nurses.assign-wards') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by nurse name or ID..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-building mr-1"></i> Department
                        </label>
                        <select name="department" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2 md:col-span-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.nurses.assign-wards') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Nurses List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($nurses as $nurse)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                        {{ substr($nurse->first_name, 0, 1) }}{{ substr($nurse->last_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $nurse->first_name }} {{ $nurse->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $nurse->nurse_id }}</p>
                                        @if($nurse->department)
                                            <span class="inline-flex items-center mt-1 px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fa fa-building mr-1"></i> {{ $nurse->department->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $nurse->shift == 'day' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($nurse->shift == 'night' ? 'bg-indigo-100 text-indigo-800' : 
                                       'bg-purple-100 text-purple-800') }}">
                                    @if($nurse->shift == 'day')
                                        <i class="fa fa-sun mr-1"></i> Day
                                    @elseif($nurse->shift == 'night')
                                        <i class="fa fa-moon mr-1"></i> Night
                                    @else
                                        <i class="fa fa-sync mr-1"></i> Rotating
                                    @endif
                                </span>
                            </div>

                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-envelope text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $nurse->email }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-phone text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $nurse->phone }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa fa-user-graduate text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $nurse->qualification }}</span>
                                </div>
                            </div>

                            <!-- Ward Assignment Section -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <i class="fa fa-hospital text-teal-600 mr-2"></i> Ward Assignment
                                </h4>
                                <div class="flex gap-2">
                                    <select class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500 text-sm">
                                        <option value="">Select Ward...</option>
                                        <option>ICU - Intensive Care Unit</option>
                                        <option>CCU - Cardiac Care Unit</option>
                                        <option>Pediatric Ward</option>
                                        <option>Maternity Ward</option>
                                        <option>General Ward A</option>
                                        <option>General Ward B</option>
                                        <option>Emergency Department</option>
                                        <option>Operating Theater</option>
                                    </select>
                                    <button class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm transition">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </div>
                                
                                <!-- Currently Assigned (Placeholder) -->
                                <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="text-xs text-green-700 dark:text-green-300">
                                            <i class="fa fa-check-circle mr-1"></i> Currently: <span class="font-semibold">Not Assigned</span>
                                        </div>
                                        <button class="text-xs text-red-600 hover:text-red-800 dark:text-red-400">
                                            <i class="fa fa-times-circle"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-4 flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('hms.nurses.show', $nurse) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-eye mr-1"></i> View Profile
                                </a>
                                <a href="{{ route('hms.nurses.edit', $nurse) }}" class="flex-1 text-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-user-nurse text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No nurses found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Adjust your search or add new nurses</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($nurses->hasPages())
                <div class="mt-6">
                    {{ $nurses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

