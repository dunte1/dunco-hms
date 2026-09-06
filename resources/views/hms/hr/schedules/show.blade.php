<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hr.schedules.index') }}" class="hover:text-blue-600">Schedules</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Details</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-clock text-orange-600 mr-3"></i>Schedule Details</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2"></div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><h3 class="text-sm font-medium text-gray-500">Employee</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $schedule->employee->first_name ?? 'N/A' }} {{ $schedule->employee->last_name ?? '' }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Schedule Date</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $schedule->schedule_date ? \Carbon\Carbon::parse($schedule->schedule_date)->format('M d, Y') : 'N/A' }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Start Time</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $schedule->start_time ?? 'N/A' }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">End Time</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $schedule->end_time ?? 'N/A' }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Shift Type</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $schedule->shift_type ?? '')) }}</p></div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.hr.schedules.edit', $schedule) }}" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <a href="{{ route('hms.hr.schedules.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
