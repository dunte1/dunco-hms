<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.visitors.index') }}" class="hover:text-purple-600">Visitors</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Visitor</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-purple-600 mr-3"></i>Edit Visitor</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                <form method="POST" action="{{ route('hms.visitors.update', $visitor) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visitor Name <span class="text-red-500">*</span></label>
                            <input type="text" name="visitor_name" required value="{{ old('visitor_name', $visitor->visitor_name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('visitor_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone <span class="text-red-500">*</span></label>
                            <input type="tel" name="visitor_phone" required value="{{ old('visitor_phone', $visitor->visitor_phone) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('visitor_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="visitor_email" value="{{ old('visitor_email', $visitor->visitor_email) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('visitor_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visitor Type <span class="text-red-500">*</span></label>
                            <select name="visitor_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="family" {{ old('visitor_type', $visitor->visitor_type) === 'family' ? 'selected' : '' }}>Family</option>
                                <option value="friend" {{ old('visitor_type', $visitor->visitor_type) === 'friend' ? 'selected' : '' }}>Friend</option>
                                <option value="representative" {{ old('visitor_type', $visitor->visitor_type) === 'representative' ? 'selected' : '' }}>Representative</option>
                                <option value="delivery" {{ old('visitor_type', $visitor->visitor_type) === 'delivery' ? 'selected' : '' }}>Delivery</option>
                                <option value="other" {{ old('visitor_type', $visitor->visitor_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('visitor_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Purpose</label>
                            <input type="text" name="purpose" value="{{ old('purpose', $visitor->purpose) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('purpose')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                            <input type="text" name="department" value="{{ old('department', $visitor->department) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('department')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $visitor->notes) }}</textarea>
                            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.visitors.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
