<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-calendar-check text-pink-600 mr-3"></i>
                        Nurse Duty Roster
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage nurse shift schedules and assignments</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-print mr-2"></i> Print Roster
                    </button>
                    <a href="{{ route('hms.nurses.index') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-users mr-2"></i> All Nurses
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
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

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Rotating</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['rotating'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-sync text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.nurses.duty-roster') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-building mr-1"></i> Department
                        </label>
                        <select name="department" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-clock mr-1"></i> Shift
                        </label>
                        <select name="shift" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500">
                            <option value="">All Shifts</option>
                            <option value="day" {{ request('shift') == 'day' ? 'selected' : '' }}>Day Shift</option>
                            <option value="night" {{ request('shift') == 'night' ? 'selected' : '' }}>Night Shift</option>
                            <option value="rotating" {{ request('shift') == 'rotating' ? 'selected' : '' }}>Rotating</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">
                            <i class="fa fa-filter mr-2"></i> Apply Filters
                        </button>
                        <a href="{{ route('hms.nurses.duty-roster') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Roster Grid -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fa fa-calendar-week mr-2"></i> Weekly Duty Roster
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700">Nurse</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Monday</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tuesday</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Wednesday</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thursday</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Friday</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saturday</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sunday</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($nurses as $nurse)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($nurse->first_name, 0, 1) }}{{ substr($nurse->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $nurse->first_name }} {{ $nurse->last_name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $nurse->department->name ?? 'Unassigned' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @for($day = 0; $day < 7; $day++)
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $nurse->shift == 'day' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                   ($nurse->shift == 'night' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 
                                                   'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200') }}">
                                                @if($nurse->shift == 'day')
                                                    <i class="fa fa-sun mr-1"></i> D
                                                @elseif($nurse->shift == 'night')
                                                    <i class="fa fa-moon mr-1"></i> N
                                                @else
                                                    <i class="fa fa-sync mr-1"></i> R
                                                @endif
                                            </span>
                                        </td>
                                    @endfor
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <i class="fa fa-calendar-check text-6xl mb-4"></i>
                                            <p class="text-lg font-medium">No nurses found</p>
                                            <p class="text-sm mt-2">Adjust your filters or add nurses to the system</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($nurses->hasPages())
                <div class="mb-6">
                    {{ $nurses->links() }}
                </div>
            @endif

            <!-- Legend -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">
                    <i class="fa fa-info-circle mr-1"></i> Shift Legend
                </h4>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 mr-2">
                            <i class="fa fa-sun mr-1"></i> D
                        </span>
                        <span class="text-blue-700 dark:text-blue-300">Day Shift (06:00 - 18:00)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 mr-2">
                            <i class="fa fa-moon mr-1"></i> N
                        </span>
                        <span class="text-blue-700 dark:text-blue-300">Night Shift (18:00 - 06:00)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 mr-2">
                            <i class="fa fa-sync mr-1"></i> R
                        </span>
                        <span class="text-blue-700 dark:text-blue-300">Rotating Shift</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

