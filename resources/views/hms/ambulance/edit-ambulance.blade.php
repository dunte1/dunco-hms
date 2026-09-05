<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.ambulance.index') }}" class="hover:text-red-600">Ambulances</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Ambulance</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-red-600 mr-3"></i>Edit Ambulance</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.ambulance.update-ambulance', $ambulance) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehicle Number <span class="text-red-500">*</span></label>
                            <input type="text" name="vehicle_number" required value="{{ old('vehicle_number', $ambulance->vehicle_number) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('vehicle_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehicle Type <span class="text-red-500">*</span></label>
                            <select name="vehicle_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="basic" {{ old('vehicle_type', $ambulance->vehicle_type) === 'basic' ? 'selected' : '' }}>Basic</option>
                                <option value="advanced" {{ old('vehicle_type', $ambulance->vehicle_type) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                <option value="critical_care" {{ old('vehicle_type', $ambulance->vehicle_type) === 'critical_care' ? 'selected' : '' }}>Critical Care</option>
                            </select>
                            @error('vehicle_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Driver Name <span class="text-red-500">*</span></label>
                            <input type="text" name="driver_name" required value="{{ old('driver_name', $ambulance->driver_name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('driver_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Driver Phone <span class="text-red-500">*</span></label>
                            <input type="tel" name="driver_phone" required value="{{ old('driver_phone', $ambulance->driver_phone) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('driver_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Equipment List</label>
                            <textarea name="equipment_list" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('equipment_list', $ambulance->equipment_list) }}</textarea>
                            @error('equipment_list')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.ambulance.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
