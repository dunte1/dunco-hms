<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.drug-interactions.index') }}" class="hover:text-blue-600">Drug Interactions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Add New Rule</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-plus text-red-600 mr-3"></i>
                    Add Drug Interaction Rule
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.drug-interactions.store') }}" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Drug A <span class="text-red-500">*</span></label>
                            <select name="drug_a_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                                <option value="">Select Drug</option>
                                @foreach($medicines as $id => $name)
                                    <option value="{{ $id }}" {{ old('drug_a_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('drug_a_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Drug B <span class="text-red-500">*</span></label>
                            <select name="drug_b_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                                <option value="">Select Drug</option>
                                @foreach($medicines as $id => $name)
                                    <option value="{{ $id }}" {{ old('drug_b_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('drug_b_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Severity <span class="text-red-500">*</span></label>
                            <select name="severity" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                                <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical - Do not co-prescribe</option>
                                <option value="severe" {{ old('severity') == 'severe' ? 'selected' : '' }}>Severe - Avoid if possible</option>
                                <option value="moderate" {{ old('severity', 'moderate') == 'moderate' ? 'selected' : '' }}>Moderate - Monitor closely</option>
                                <option value="mild" {{ old('severity') == 'mild' ? 'selected' : '' }}>Mild - Low risk</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Source</label>
                            <input type="text" name="source" value="{{ old('source') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500" placeholder="e.g., DrugBank, Medscape">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="2" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500" placeholder="Brief description of the interaction...">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clinical Effect</label>
                        <textarea name="clinical_effect" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500" placeholder="What happens when these drugs are taken together...">{{ old('clinical_effect') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Management Advice</label>
                        <textarea name="management_advice" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500" placeholder="How to manage this interaction...">{{ old('management_advice') }}</textarea>
                    </div>
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-save mr-2"></i> Save Rule</button>
                        <a href="{{ route('hms.drug-interactions.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-times mr-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
