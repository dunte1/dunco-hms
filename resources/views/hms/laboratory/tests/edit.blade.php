<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.laboratory.tests.index') }}" class="hover:text-blue-600">Laboratory Tests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $test->test_name }}</span>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-blue-600 mr-3"></i>
                            Edit Laboratory Test
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update test information and specifications</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.laboratory.tests.index') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.laboratory.tests.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.laboratory.tests.update', $test) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-info-circle text-blue-600 mr-2"></i>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="test_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Name *</label>
                                <input type="text" name="test_name" id="test_name" value="{{ old('test_name', $test->test_name) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                @error('test_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="test_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Code</label>
                                <input type="text" name="test_code" id="test_code" value="{{ old('test_code', $test->test_code) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., CBC, LFT, KFT">
                                @error('test_code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                                <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
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
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Test Specifications -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-flask text-purple-600 mr-2"></i>
                            Test Specifications
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="normal_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Normal Range</label>
                                <input type="text" name="normal_range" id="normal_range" value="{{ old('normal_range', $test->normal_range) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 3.5-5.5 g/dL">
                                @error('normal_range')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit</label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit', $test->unit) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., mg/dL, %, cells/μL">
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Test Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-clipboard-list text-green-600 mr-2"></i>
                            Test Details
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter test description...">{{ old('description', $test->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Instructions</label>
                                <textarea name="instructions" id="instructions" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter patient preparation instructions...">{{ old('instructions', $test->instructions) }}</textarea>
                                @error('instructions')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="methodology" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Methodology</label>
                                <textarea name="methodology" id="methodology" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter test methodology...">{{ old('methodology', $test->methodology) }}</textarea>
                                @error('methodology')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Test Requirements -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-clock text-orange-600 mr-2"></i>
                            Test Requirements
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="sample_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sample Type</label>
                                <select name="sample_type" id="sample_type"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Sample Type</option>
                                    <option value="blood" {{ old('sample_type', $test->sample_type) == 'blood' ? 'selected' : '' }}>Blood</option>
                                    <option value="urine" {{ old('sample_type', $test->sample_type) == 'urine' ? 'selected' : '' }}>Urine</option>
                                    <option value="stool" {{ old('sample_type', $test->sample_type) == 'stool' ? 'selected' : '' }}>Stool</option>
                                    <option value="sputum" {{ old('sample_type', $test->sample_type) == 'sputum' ? 'selected' : '' }}>Sputum</option>
                                    <option value="cerebrospinal_fluid" {{ old('sample_type', $test->sample_type) == 'cerebrospinal_fluid' ? 'selected' : '' }}>CSF</option>
                                    <option value="tissue" {{ old('sample_type', $test->sample_type) == 'tissue' ? 'selected' : '' }}>Tissue</option>
                                    <option value="other" {{ old('sample_type', $test->sample_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sample_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="turnaround_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Turnaround Time (Hours)</label>
                                <input type="number" name="turnaround_time" id="turnaround_time" value="{{ old('turnaround_time', $test->turnaround_time) }}" min="0" step="0.5"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 24">
                                @error('turnaround_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="is_active" id="is_active"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1" {{ old('is_active', $test->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $test->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.laboratory.tests.index') }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Test
                        </button>
                    </div>
                </form>
            </div>

            <!-- Test Information -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fa fa-info-circle text-blue-600 dark:text-blue-400 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Test Information</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                            This test was created on {{ $test->created_at->format('M d, Y') }} and last updated on {{ $test->updated_at->format('M d, Y') }}.
                            Test ID: #{{ $test->id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>