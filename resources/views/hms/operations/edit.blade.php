<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.operations.index') }}" class="hover:text-red-600">Operations</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Operation</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-red-600 mr-3"></i>Edit Operation Report</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.operations.update', $operation) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Operation Name <span class="text-red-500">*</span></label>
                            <input type="text" name="operation_name" required value="{{ old('operation_name', $operation->operation_name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('operation_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient <span class="text-red-500">*</span></label>
                            <select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $operation->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} ({{ $patient->patient_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Operation Date <span class="text-red-500">*</span></label>
                            <input type="date" name="operation_date" required value="{{ old('operation_date', $operation->operation_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('operation_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Outcome <span class="text-red-500">*</span></label>
                            <select name="outcome" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="successful" {{ old('outcome', $operation->outcome) === 'successful' ? 'selected' : '' }}>Successful</option>
                                <option value="complications" {{ old('outcome', $operation->outcome) === 'complications' ? 'selected' : '' }}>Complications</option>
                                <option value="unsuccessful" {{ old('outcome', $operation->outcome) === 'unsuccessful' ? 'selected' : '' }}>Unsuccessful</option>
                            </select>
                            @error('outcome')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea name="operation_description" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('operation_description', $operation->operation_description) }}</textarea>
                            @error('operation_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.operations.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
