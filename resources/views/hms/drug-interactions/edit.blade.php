<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.drug-interactions.index') }}" class="hover:text-blue-600">Drug Interactions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Rule</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-edit text-red-600 mr-3"></i>
                    Edit Drug Interaction Rule
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.drug-interactions.update', $drugInteraction) }}" class="p-6 space-y-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Drug A</label>
                            <input type="text" value="{{ $drugInteraction->drugA->name }}" disabled class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Drug B</label>
                            <input type="text" value="{{ $drugInteraction->drugB->name }}" disabled class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Severity <span class="text-red-500">*</span></label>
                            <select name="severity" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="critical" {{ old('severity', $drugInteraction->severity) == 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="severe" {{ old('severity', $drugInteraction->severity) == 'severe' ? 'selected' : '' }}>Severe</option>
                                <option value="moderate" {{ old('severity', $drugInteraction->severity) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="mild" {{ old('severity', $drugInteraction->severity) == 'mild' ? 'selected' : '' }}>Mild</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Source</label>
                            <input type="text" name="source" value="{{ old('source', $drugInteraction->source) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="2" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $drugInteraction->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clinical Effect</label>
                        <textarea name="clinical_effect" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('clinical_effect', $drugInteraction->clinical_effect) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Management Advice</label>
                        <textarea name="management_advice" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('management_advice', $drugInteraction->management_advice) }}</textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $drugInteraction->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                    </div>
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.drug-interactions.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg"><i class="fa fa-times mr-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
