<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-calendar-plus text-indigo-600 mr-3"></i>
                    Create Employee Schedule
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Schedule a new shift for an employee</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.hr.schedules.store') }}" method="POST">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-user text-indigo-600 mr-2"></i> Employee Information
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Employee <span class="text-red-500">*</span>
                            </label>
                            <select name="employee_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 @error('employee_id') border-red-500 @enderror">
                                <option value="">Choose an employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Schedule Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-clock text-indigo-600 mr-2"></i> Schedule Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Schedule Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="schedule_date" value="{{ old('schedule_date', date('Y-m-d')) }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 @error('schedule_date') border-red-500 @enderror">
                                @error('schedule_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Shift Type <span class="text-red-500">*</span>
                                </label>
                                <select name="shift_type" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 @error('shift_type') border-red-500 @enderror">
                                    <option value="">Select shift type...</option>
                                    <option value="morning" {{ old('shift_type') == 'morning' ? 'selected' : '' }}>
                                        <i class="fa fa-sun"></i> Morning Shift
                                    </option>
                                    <option value="afternoon" {{ old('shift_type') == 'afternoon' ? 'selected' : '' }}>
                                        Afternoon Shift
                                    </option>
                                    <option value="night" {{ old('shift_type') == 'night' ? 'selected' : '' }}>
                                        Night Shift
                                    </option>
                                    <option value="on_call" {{ old('shift_type') == 'on_call' ? 'selected' : '' }}>
                                        On Call
                                    </option>
                                </select>
                                @error('shift_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Start Time <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 @error('start_time') border-red-500 @enderror">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    End Time <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="end_time" value="{{ old('end_time', '16:00') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 @error('end_time') border-red-500 @enderror">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-sticky-note text-indigo-600 mr-2"></i> Additional Information
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes
                            </label>
                            <textarea name="notes" rows="4" placeholder="Add any special instructions or notes..."
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Quick Shift Templates -->
                    <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg">
                        <h4 class="text-sm font-semibold text-indigo-900 dark:text-indigo-300 mb-2">
                            <i class="fa fa-magic mr-1"></i> Quick Shift Templates
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setShift('morning', '06:00', '14:00')" 
                                class="px-3 py-1 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-800 dark:text-yellow-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-sun mr-1"></i> Morning (6 AM - 2 PM)
                            </button>
                            <button type="button" onclick="setShift('afternoon', '14:00', '22:00')" 
                                class="px-3 py-1 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900 dark:hover:bg-orange-800 text-orange-800 dark:text-orange-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-cloud-sun mr-1"></i> Afternoon (2 PM - 10 PM)
                            </button>
                            <button type="button" onclick="setShift('night', '22:00', '06:00')" 
                                class="px-3 py-1 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:hover:bg-indigo-800 text-indigo-800 dark:text-indigo-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-moon mr-1"></i> Night (10 PM - 6 AM)
                            </button>
                            <button type="button" onclick="setShift('on_call', '00:00', '23:59')" 
                                class="px-3 py-1 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-phone mr-1"></i> On Call (24 Hours)
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.hr.schedules.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                            <i class="fa fa-save mr-2"></i> Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setShift(type, start, end) {
            document.querySelector('select[name="shift_type"]').value = type;
            document.querySelector('input[name="start_time"]').value = start;
            document.querySelector('input[name="end_time"]').value = end;
        }
    </script>
</x-app-layout>

