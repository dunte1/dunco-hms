<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.packages.index') }}" class="hover:text-emerald-600">Health Packages</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <a href="{{ route('hms.packages.show', $package) }}" class="hover:text-emerald-600">{{ $package->name }}</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-emerald-600 mr-3"></i>
                            Edit Health Package
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update package information and services</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.packages.show', $package) }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.packages.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.packages.update', $package) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-info-circle text-emerald-600 mr-2"></i>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Package Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Package Price *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price', $package->price) }}" step="0.01" min="0" required
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="duration_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Days)</label>
                                <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $package->duration_days) }}" min="1"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="e.g., 30">
                                @error('duration_days')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="is_active" id="is_active"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="1" {{ old('is_active', $package->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $package->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Package Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-clipboard-list text-blue-600 mr-2"></i>
                            Package Description
                        </h3>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Enter package description...">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Package Services -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-list-ul text-purple-600 mr-2"></i>
                            Package Services
                        </h3>
                        
                        <div id="services-container">
                            @foreach($package->items as $index => $item)
                                <div class="service-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Service {{ $index + 1 }}</h4>
                                        @if($index > 0)
                                            <button type="button" onclick="removeService(this)" class="text-red-600 hover:text-red-800">
                                                <i class="fa fa-trash"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type *</label>
                                            <select name="services[{{ $index }}][service_type]" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                                <option value="">Select Service Type</option>
                                                <option value="consultation" {{ old('services.'.$index.'.service_type', $item->service_type) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                                <option value="laboratory" {{ old('services.'.$index.'.service_type', $item->service_type) == 'laboratory' ? 'selected' : '' }}>Laboratory Test</option>
                                                <option value="radiology" {{ old('services.'.$index.'.service_type', $item->service_type) == 'radiology' ? 'selected' : '' }}>Radiology Test</option>
                                                <option value="pharmacy" {{ old('services.'.$index.'.service_type', $item->service_type) == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                                <option value="procedure" {{ old('services.'.$index.'.service_type', $item->service_type) == 'procedure' ? 'selected' : '' }}>Procedure</option>
                                                <option value="other" {{ old('services.'.$index.'.service_type', $item->service_type) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name *</label>
                                            <input type="text" name="services[{{ $index }}][service_name]" value="{{ old('services.'.$index.'.service_name', $item->service_name) }}" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Service name">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                                            <input type="number" name="services[{{ $index }}][quantity]" value="{{ old('services.'.$index.'.quantity', $item->quantity) }}" min="1" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                                </div>
                                                <input type="number" name="services[{{ $index }}][unit_price]" value="{{ old('services.'.$index.'.unit_price', $item->unit_price) }}" step="0.01" min="0" required
                                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Price</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                                </div>
                                                <input type="number" name="services[{{ $index }}][total_price]" value="{{ old('services.'.$index.'.total_price', $item->total_price) }}" step="0.01" min="0" required
                                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                            <textarea name="services[{{ $index }}][description]" rows="2"
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Service description...">{{ old('services.'.$index.'.description', $item->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addService()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Another Service
                        </button>
                    </div>

                    <!-- Package Terms -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-file-contract text-orange-600 mr-2"></i>
                            Package Terms & Conditions
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="terms_conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terms & Conditions</label>
                                <textarea name="terms_conditions" id="terms_conditions" rows="4"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Enter terms and conditions...">{{ old('terms_conditions', $package->terms_conditions) }}</textarea>
                                @error('terms_conditions')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Enter any additional notes...">{{ old('notes', $package->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.packages.show', $package) }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Package
                        </button>
                    </div>
                </form>
            </div>

            <!-- Package Information -->
            <div class="mt-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fa fa-info-circle text-emerald-600 dark:text-emerald-400 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Package Information</h4>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 mt-1">
                            This package was created on {{ $package->created_at->format('M d, Y') }} and last updated on {{ $package->updated_at->format('M d, Y') }}.
                            Package ID: #{{ $package->id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let serviceIndex = {{ $package->items->count() }};

        function addService() {
            const container = document.getElementById('services-container');
            const serviceHtml = `
                <div class="service-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Service ${serviceIndex + 1}</h4>
                        <button type="button" onclick="removeService(this)" class="text-red-600 hover:text-red-800">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type *</label>
                            <select name="services[${serviceIndex}][service_type]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select Service Type</option>
                                <option value="consultation">Consultation</option>
                                <option value="laboratory">Laboratory Test</option>
                                <option value="radiology">Radiology Test</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="procedure">Procedure</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name *</label>
                            <input type="text" name="services[${serviceIndex}][service_name]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Service name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                            <input type="number" name="services[${serviceIndex}][quantity]" min="1" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                </div>
                                <input type="number" name="services[${serviceIndex}][unit_price]" step="0.01" min="0" required
                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Price</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                </div>
                                <input type="number" name="services[${serviceIndex}][total_price]" step="0.01" min="0" required
                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="services[${serviceIndex}][description]" rows="2"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Service description..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', serviceHtml);
            serviceIndex++;
        }

        function removeService(button) {
            button.closest('.service-item').remove();
        }

        // Calculate totals when quantity or unit price changes
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('[quantity]') || e.target.name && e.target.name.includes('[unit_price]')) {
                const service = e.target.closest('.service-item');
                const quantity = parseFloat(service.querySelector('input[name*="[quantity]"]').value) || 0;
                const unitPrice = parseFloat(service.querySelector('input[name*="[unit_price]"]').value) || 0;
                const total = quantity * unitPrice;
                service.querySelector('input[name*="[total_price]"]').value = total.toFixed(2);
            }
        });
    </script>
</x-app-layout>