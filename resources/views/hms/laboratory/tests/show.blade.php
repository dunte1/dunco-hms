<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.laboratory.tests.index') }}" class="hover:text-blue-600">Laboratory Tests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $labTest->test_name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-flask text-blue-600 mr-3"></i>
                            {{ $labTest->test_name }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $labTest->category->name ?? 'No category' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.laboratory.tests.edit', $labTest) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        <a href="{{ route('hms.laboratory.tests.index') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Test Information Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                <i class="fa fa-flask"></i>
                            </div>
                            <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $labTest->test_name }}</h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                                {{ $labTest->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fa fa-{{ $labTest->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $labTest->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-layer-group text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Category</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $labTest->category->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-dollar-sign text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Price</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($labTest->price, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Test Name</label>
                                <div class="text-gray-900 dark:text-white font-semibold">{{ $labTest->test_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Category</label>
                                <div class="text-gray-900 dark:text-white">{{ $labTest->category->name ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Price</label>
                                <div class="text-gray-900 dark:text-white font-semibold text-lg">${{ number_format($labTest->price, 2) }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $labTest->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $labTest->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            @if($labTest->normal_range)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Normal Range</label>
                                <div class="text-gray-900 dark:text-white">{{ $labTest->normal_range }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($labTest->description)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-file-alt text-blue-600 mr-2"></i> Description
                        </h3>
                        <div class="text-gray-900 dark:text-white">{{ $labTest->description }}</div>
                    </div>
                    @endif

                    <!-- Instructions -->
                    @if($labTest->instructions)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Instructions
                        </h3>
                        <div class="text-gray-900 dark:text-white whitespace-pre-line">{{ $labTest->instructions }}</div>
                    </div>
                    @endif

                    <!-- Usage Statistics -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-chart-line text-blue-600 mr-2"></i> Usage Statistics
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                                    {{ $labTest->requestItems()->count() }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Requests</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-300">
                                    {{ $labTest->requestItems()->whereHas('labRequest', function($q) { $q->where('status', 'completed'); })->count() }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Completed</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-300">
                                    ${{ number_format($labTest->requestItems()->sum('price') ?? 0, 2) }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

