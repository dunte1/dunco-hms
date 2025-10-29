<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.radiology.tests.index') }}" class="hover:text-purple-600">Radiology Tests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $test->test_name }}</span>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-purple-600 mr-3"></i>
                            Edit Radiology Test
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update radiology test information and specifications</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.radiology.tests.index') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.radiology.tests.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.radiology.tests.update', $test) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-info-circle text-purple-600 mr-2"></i>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="test_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Name *</label>
                                <input type="text" name="test_name" id="test_name" value="{{ old('test_name', $test->test_name) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                @error('test_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                                <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $test->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price', $test->price) }}" step="0.01" min="0" required
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="is_active" id="is_active"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="1" {{ old('is_active', $test->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $test->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Test Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-clipboard-list text-blue-600 mr-2"></i>
                            Test Description
                        </h3>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter test description...">{{ old('description', $test->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Patient Preparation -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-user-injured text-green-600 mr-2"></i>
                            Patient Preparation
                        </h3>
                        <div>
                            <label for="preparation_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preparation Instructions</label>
                            <textarea name="preparation_instructions" id="preparation_instructions" rows="4"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter patient preparation instructions...">{{ old('preparation_instructions', $test->preparation_instructions) }}</textarea>
                            @error('preparation_instructions')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-cogs text-orange-600 mr-2"></i>
                            Technical Specifications
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="imaging_modality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imaging Modality</label>
                                <select name="imaging_modality" id="imaging_modality"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Modality</option>
                                    <option value="X-Ray" {{ old('imaging_modality', $test->imaging_modality) == 'X-Ray' ? 'selected' : '' }}>X-Ray</option>
                                    <option value="CT Scan" {{ old('imaging_modality', $test->imaging_modality) == 'CT Scan' ? 'selected' : '' }}>CT Scan</option>
                                    <option value="MRI" {{ old('imaging_modality', $test->imaging_modality) == 'MRI' ? 'selected' : '' }}>MRI</option>
                                    <option value="Ultrasound" {{ old('imaging_modality', $test->imaging_modality) == 'Ultrasound' ? 'selected' : '' }}>Ultrasound</option>
                                    <option value="Mammography" {{ old('imaging_modality', $test->imaging_modality) == 'Mammography' ? 'selected' : '' }}>Mammography</option>
                                    <option value="Nuclear Medicine" {{ old('imaging_modality', $test->imaging_modality) == 'Nuclear Medicine' ? 'selected' : '' }}>Nuclear Medicine</option>
                                    <option value="PET Scan" {{ old('imaging_modality', $test->imaging_modality) == 'PET Scan' ? 'selected' : '' }}>PET Scan</option>
                                    <option value="Fluoroscopy" {{ old('imaging_modality', $test->imaging_modality) == 'Fluoroscopy' ? 'selected' : '' }}>Fluoroscopy</option>
                                    <option value="Other" {{ old('imaging_modality', $test->imaging_modality) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('imaging_modality')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="body_part" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Body Part</label>
                                <input type="text" name="body_part" id="body_part" value="{{ old('body_part', $test->body_part) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="e.g., Chest, Abdomen, Brain">
                                @error('body_part')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contrast_required" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contrast Required</label>
                                <select name="contrast_required" id="contrast_required"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="0" {{ old('contrast_required', $test->contrast_required) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('contrast_required', $test->contrast_required) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('contrast_required')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Minutes)</label>
                                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" min="0" step="5"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="e.g., 30">
                                @error('duration_minutes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Safety & Contraindications -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-exclamation-triangle text-red-600 mr-2"></i>
                            Safety & Contraindications
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="contraindications" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraindications</label>
                                <textarea name="contraindications" id="contraindications" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Enter contraindications...">{{ old('contraindications', $test->contraindications) }}</textarea>
                                @error('contraindications')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="safety_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Safety Notes</label>
                                <textarea name="safety_notes" id="safety_notes" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Enter safety notes...">{{ old('safety_notes', $test->safety_notes) }}</textarea>
                                @error('safety_notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-info text-indigo-600 mr-2"></i>
                            Additional Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="report_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Report Template</label>
                                <textarea name="report_template" id="report_template" rows="4"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Enter report template...">{{ old('report_template', $test->report_template) }}</textarea>
                                @error('report_template')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Enter any additional notes...">{{ old('notes', $test->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.radiology.tests.index') }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Test
                        </button>
                    </div>
                </form>
            </div>

            <!-- Test Information -->
            <div class="mt-6 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fa fa-info-circle text-purple-600 dark:text-purple-400 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200">Test Information</h4>
                        <p class="text-sm text-purple-700 dark:text-purple-300 mt-1">
                            This test was created on {{ $test->created_at->format('M d, Y') }} and last updated on {{ $test->updated_at->format('M d, Y') }}.
                            Test ID: #{{ $test->id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>