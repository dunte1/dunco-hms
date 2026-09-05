<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.mortuary.index') }}" class="hover:text-gray-600">Mortuary</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit {{ $record->body_id }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-gray-600 mr-3"></i>Edit Mortuary Record</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2"></div>
                <form method="POST" action="{{ route('hms.mortuary.update', $record) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Body ID <span class="text-red-500">*</span></label>
                            <input type="text" name="body_id" required value="{{ old('body_id', $record->body_id) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('body_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="stored" {{ old('status', $record->status) === 'stored' ? 'selected' : '' }}>Stored</option>
                                <option value="released" {{ old('status', $record->status) === 'released' ? 'selected' : '' }}>Released</option>
                                <option value="disposed" {{ old('status', $record->status) === 'disposed' ? 'selected' : '' }}>Disposed</option>
                            </select>
                            @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Storage Location</label>
                            <input type="text" name="storage_location" value="{{ old('storage_location', $record->storage_location) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('storage_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Family Contact Name</label>
                            <input type="text" name="family_contact_name" value="{{ old('family_contact_name', $record->family_contact_name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('family_contact_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Family Contact Phone</label>
                            <input type="tel" name="family_contact_phone" value="{{ old('family_contact_phone', $record->family_contact_phone) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('family_contact_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cause of Death</label>
                            <textarea name="cause_of_death" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('cause_of_death', $record->cause_of_death) }}</textarea>
                            @error('cause_of_death')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.mortuary.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
