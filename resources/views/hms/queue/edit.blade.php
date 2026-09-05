<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.queue.index') }}" class="hover:text-indigo-600">Queue Management</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Queue</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-indigo-600 mr-3"></i>Edit Queue</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                <form method="POST" action="{{ route('hms.queue.update', $queue) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient Name <span class="text-red-500">*</span></label>
                            <input type="text" name="patient_name" required value="{{ old('patient_name', $queue->patient_name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('patient_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="tel" name="patient_phone" value="{{ old('patient_phone', $queue->patient_phone) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('patient_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department <span class="text-red-500">*</span></label>
                            <input type="text" name="department" required value="{{ old('department', $queue->department) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('department')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority <span class="text-red-500">*</span></label>
                            <select name="priority" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="normal" {{ old('priority', $queue->priority) === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="low" {{ old('priority', $queue->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="high" {{ old('priority', $queue->priority) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="emergency" {{ old('priority', $queue->priority) === 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            @error('priority')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="waiting" {{ old('status', $queue->status) === 'waiting' ? 'selected' : '' }}>Waiting</option>
                                <option value="called" {{ old('status', $queue->status) === 'called' ? 'selected' : '' }}>Called</option>
                                <option value="in_progress" {{ old('status', $queue->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $queue->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $queue->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.queue.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
