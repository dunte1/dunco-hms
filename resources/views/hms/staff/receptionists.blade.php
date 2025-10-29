<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-tie text-teal-600 mr-3"></i>
                        Receptionists Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage front desk staff and their schedules</p>
                </div>
                <a href="{{ route('hms.staff.receptionists.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-user-plus mr-2"></i> Add Receptionist
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Receptionists</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Day Shift</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['day_shift'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-sun text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Night Shift</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['night_shift'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-moon text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Joined This Month</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['added_this_month'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-plus text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.staff.receptionists') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name, ID, email, or phone..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-filter mr-1"></i> Shift
                        </label>
                        <select name="shift" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                            <option value="">All Shifts</option>
                            <option value="day" {{ request('shift') == 'day' ? 'selected' : '' }}>Day Shift</option>
                            <option value="night" {{ request('shift') == 'night' ? 'selected' : '' }}>Night Shift</option>
                            <option value="rotating" {{ request('shift') == 'rotating' ? 'selected' : '' }}>Rotating</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2 md:col-span-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.staff.receptionists') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Receptionists Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Receptionist</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Shift</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joining Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Salary</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($receptionists as $receptionist)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($receptionist->first_name, 0, 1) }}{{ substr($receptionist->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $receptionist->first_name }} {{ $receptionist->last_name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $receptionist->receptionist_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            <i class="fa fa-envelope text-gray-400 mr-1"></i> {{ $receptionist->email }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <i class="fa fa-phone text-gray-400 mr-1"></i> {{ $receptionist->phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                            {{ $receptionist->shift == 'day' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                               ($receptionist->shift == 'night' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 
                                               'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200') }}">
                                            @if($receptionist->shift == 'day')
                                                <i class="fa fa-sun mr-1"></i> Day
                                            @elseif($receptionist->shift == 'night')
                                                <i class="fa fa-moon mr-1"></i> Night
                                            @else
                                                <i class="fa fa-sync mr-1"></i> Rotating
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($receptionist->joining_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        @if($receptionist->salary)
                                            ${{ number_format($receptionist->salary, 2) }}
                                        @else
                                            <span class="text-gray-400">Not set</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('hms.staff.receptionists.show', $receptionist) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('hms.staff.receptionists.edit', $receptionist) }}" class="text-green-600 hover:text-green-900 dark:text-green-400" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hms.staff.receptionists.destroy', $receptionist) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this receptionist?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <i class="fa fa-user-tie text-6xl mb-4"></i>
                                            <p class="text-lg font-medium">No receptionists found</p>
                                            <p class="text-sm mt-2">Start by adding a new receptionist</p>
                                            <a href="{{ route('hms.staff.receptionists.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">
                                                <i class="fa fa-user-plus mr-2"></i> Add First Receptionist
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($receptionists->hasPages())
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $receptionists->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

