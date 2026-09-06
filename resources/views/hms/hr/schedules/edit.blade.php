<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hr.schedules.index') }}" class="hover:text-blue-600">Schedules</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-orange-600 mr-3"></i>Edit Schedule</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.hr.schedules.update', $schedule) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee <span class="text-red-500">*</span></label>
                            <select name="employee_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id', $schedule->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Day of Week <span class="text-red-500">*</span></label>
                            <input type="text" name="day_of_week" value="{{ old('day_of_week', $schedule->day_of_week ?? '') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Time <span class="text-red-500">*</span></label>
                            <input type="time" name="start_time" value="{{ old('start_time', $schedule->start_time ?? '') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Time <span class="text-red-500">*</span></label>
                            <input type="time" name="end_time" value="{{ old('end_time', $schedule->end_time ?? '') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.hr.schedules.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
