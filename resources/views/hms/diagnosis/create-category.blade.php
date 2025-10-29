<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.diagnosis.categories') }}" class="hover:text-indigo-600">Diagnosis Categories</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Add Category</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-plus text-indigo-600 mr-3"></i>
                    Add Diagnosis Category
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new diagnosis classification category</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.diagnosis.categories.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Category Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Category Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., Cardiovascular, Respiratory, Infectious Diseases">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., CARD, RESP, INF">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea name="description" rows="4"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Brief description of this diagnosis category..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Common Categories -->
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-bolt text-indigo-600 mr-2"></i> Common Categories
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" onclick="fillCategory('cardiovascular')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Cardiovascular
                            </button>
                            <button type="button" onclick="fillCategory('respiratory')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Respiratory
                            </button>
                            <button type="button" onclick="fillCategory('infectious')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Infectious
                            </button>
                            <button type="button" onclick="fillCategory('neurological')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Neurological
                            </button>
                            <button type="button" onclick="fillCategory('gastrointestinal')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Gastrointestinal
                            </button>
                            <button type="button" onclick="fillCategory('endocrine')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Endocrine
                            </button>
                            <button type="button" onclick="fillCategory('musculoskeletal')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Musculoskeletal
                            </button>
                            <button type="button" onclick="fillCategory('dermatological')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Dermatological
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Add Category
                        </button>
                        <a href="{{ route('hms.diagnosis.categories') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillCategory(type) {
            const categories = {
                cardiovascular: {
                    name: "Cardiovascular Diseases",
                    code: "CARD",
                    description: "Diseases and conditions affecting the heart and blood vessels, including coronary artery disease, heart failure, and arrhythmias."
                },
                respiratory: {
                    name: "Respiratory Diseases",
                    code: "RESP",
                    description: "Diseases affecting the lungs and respiratory system, including asthma, COPD, pneumonia, and bronchitis."
                },
                infectious: {
                    name: "Infectious Diseases",
                    code: "INF",
                    description: "Diseases caused by pathogenic microorganisms such as bacteria, viruses, parasites, or fungi."
                },
                neurological: {
                    name: "Neurological Disorders",
                    code: "NEUR",
                    description: "Disorders affecting the brain, spinal cord, and nerves, including stroke, epilepsy, and Parkinson's disease."
                },
                gastrointestinal: {
                    name: "Gastrointestinal Disorders",
                    code: "GI",
                    description: "Diseases affecting the digestive system, including stomach, intestines, liver, and pancreas."
                },
                endocrine: {
                    name: "Endocrine Disorders",
                    code: "ENDO",
                    description: "Disorders affecting hormone-producing glands, including diabetes, thyroid disorders, and adrenal disorders."
                },
                musculoskeletal: {
                    name: "Musculoskeletal Disorders",
                    code: "MSK",
                    description: "Conditions affecting muscles, bones, and joints, including arthritis, osteoporosis, and back pain."
                },
                dermatological: {
                    name: "Dermatological Conditions",
                    code: "DERM",
                    description: "Diseases and conditions affecting the skin, hair, and nails, including eczema, psoriasis, and skin infections."
                }
            };
            
            if (categories[type]) {
                document.querySelector('input[name="name"]').value = categories[type].name;
                document.querySelector('input[name="code"]').value = categories[type].code;
                document.querySelector('textarea[name="description"]').value = categories[type].description;
            }
        }
    </script>
</x-app-layout>

