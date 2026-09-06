<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hr.attendance.index') }}" class="hover:text-blue-600">Attendance</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Details</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-calendar-check text-purple-600 mr-3"></i>Attendance Details</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2"></div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $attendance->employee->first_name ?? $attendance->user->name ?? 'N/A' }} {{ $attendance->employee->last_name ?? '' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $attendance->date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Check In</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $attendance->check_in ? $attendance->check_in->format('H:i') : 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Check Out</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">{{ ucfirst($attendance->status) }}</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Hours Worked</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ round(($attendance->hours_worked ?? 0) / 60, 1) }} hrs</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.hr.attendance.edit', $attendance) }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <a href="{{ route('hms.hr.attendance.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
