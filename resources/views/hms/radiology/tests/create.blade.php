<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.radiology.tests.index') }}" class="hover:text-purple-600">Radiology Tests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Add New Test</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-x-ray text-purple-600 mr-3"></i>
                    Add Radiology Test
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Register a new radiology test type</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.radiology.tests.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Test Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Test Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Test Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="test_name" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="e.g., Chest X-Ray, MRI Brain, CT Scan Abdomen">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="price" step="0.01" min="0" required
                                        class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea name="description" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Brief description of the test..."></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Preparation Instructions
                                </label>
                                <textarea name="preparation_instructions" rows="4"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Patient preparation instructions (fasting requirements, medications to avoid, etc.)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Common Test Templates -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-bolt text-purple-600 mr-2"></i> Common Tests
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" onclick="fillTest('xray')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                X-Ray
                            </button>
                            <button type="button" onclick="fillTest('mri')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                MRI
                            </button>
                            <button type="button" onclick="fillTest('ct')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                CT Scan
                            </button>
                            <button type="button" onclick="fillTest('ultrasound')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Ultrasound
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Add Test
                        </button>
                        <a href="{{ route('hms.radiology.tests.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillTest(type) {
            const tests = {
                xray: {
                    name: "Chest X-Ray",
                    description: "Standard chest radiograph to examine lungs, heart, and chest wall",
                    preparation: "Remove all metal objects and jewelry from chest area. Inform technician if pregnant."
                },
                mri: {
                    name: "MRI Brain",
                    description: "Magnetic resonance imaging of the brain for detailed internal images",
                    preparation: "Remove all metal objects. Inform staff of any implants, pacemakers, or metal fragments. Fasting may be required for 4 hours prior to exam."
                },
                ct: {
                    name: "CT Scan Abdomen",
                    description: "Computed tomography scan of the abdominal region",
                    preparation: "Fasting for 4-6 hours before exam. May require oral or IV contrast. Remove metal objects from abdomen area."
                },
                ultrasound: {
                    name: "Ultrasound Abdomen",
                    description: "Sonographic examination of abdominal organs",
                    preparation: "Fasting for 6-8 hours before exam. Drink plenty of water to ensure full bladder if pelvic ultrasound included."
                }
            };
            
            if (tests[type]) {
                document.querySelector('input[name="test_name"]').value = tests[type].name;
                document.querySelector('textarea[name="description"]').value = tests[type].description;
                document.querySelector('textarea[name="preparation_instructions"]').value = tests[type].preparation;
            }
        }
    </script>
</x-app-layout>

