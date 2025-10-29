<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-calendar-alt text-indigo-600 mr-3"></i>
                        Employee Schedules
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage staff work schedules and shifts</p>
                </div>
                <a href="{{ route('hms.hr.schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Schedule
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Schedules</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Today</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['today'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-day text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">This Week</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['this_week'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-week text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Approved</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['approved'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Pending</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-clock text-2xl"></i>
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
                <form method="GET" action="{{ route('hms.hr.schedules.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by employee name..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-filter mr-1"></i> Shift Type
                        </label>
                        <select name="shift_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Shifts</option>
                            <option value="morning" {{ request('shift_type') == 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="afternoon" {{ request('shift_type') == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                            <option value="night" {{ request('shift_type') == 'night' ? 'selected' : '' }}>Night</option>
                            <option value="on_call" {{ request('shift_type') == 'on_call' ? 'selected' : '' }}>On Call</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-info-circle mr-1"></i> Status
                        </label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-calendar mr-1"></i> From Date
                        </label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-calendar mr-1"></i> To Date
                        </label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div class="flex items-end gap-2 md:col-span-6">
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.hr.schedules.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                        <button type="button" onclick="window.print()" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Schedules Calendar View (Optional Enhancement) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fa fa-calendar-alt mr-2"></i> Schedule Overview
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-7 gap-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2">
                        <div class="py-2">Mon</div>
                        <div class="py-2">Tue</div>
                        <div class="py-2">Wed</div>
                        <div class="py-2">Thu</div>
                        <div class="py-2">Fri</div>
                        <div class="py-2">Sat</div>
                        <div class="py-2">Sun</div>
                    </div>
                    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                        <i class="fa fa-calendar text-4xl mb-2"></i>
                        <p class="text-sm">Calendar view - Coming soon</p>
                    </div>
                </div>
            </div>

            <!-- Schedules Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Shift Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($schedules as $schedule)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($schedule->employee->first_name, 0, 1) }}{{ substr($schedule->employee->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $schedule->employee->full_name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $schedule->employee->designation ?? 'Staff' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('l') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            <i class="fa fa-clock text-gray-400 mr-1"></i>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            to {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                            {{ $schedule->shift_type == 'morning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                               ($schedule->shift_type == 'afternoon' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : 
                                               ($schedule->shift_type == 'night' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 
                                               'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200')) }}">
                                            @if($schedule->shift_type == 'morning')
                                                <i class="fa fa-sun mr-1"></i> Morning
                                            @elseif($schedule->shift_type == 'afternoon')
                                                <i class="fa fa-cloud-sun mr-1"></i> Afternoon
                                            @elseif($schedule->shift_type == 'night')
                                                <i class="fa fa-moon mr-1"></i> Night
                                            @else
                                                <i class="fa fa-phone mr-1"></i> On Call
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $schedule->is_approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                            {{ $schedule->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-300 max-w-xs line-clamp-2">
                                            {{ $schedule->notes ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-900 dark:text-green-400" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            @if(!$schedule->is_approved)
                                            <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400" title="Approve">
                                                <i class="fa fa-check-circle"></i>
                                            </button>
                                            @endif
                                            <button class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <i class="fa fa-calendar-alt text-6xl mb-4"></i>
                                            <p class="text-lg font-medium">No schedules found</p>
                                            <p class="text-sm mt-2">Start by creating a new employee schedule</p>
                                            <a href="{{ route('hms.hr.schedules.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                                <i class="fa fa-plus mr-2"></i> Create First Schedule
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($schedules->hasPages())
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $schedules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
