<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.radiology.tests.index') }}" class="hover:text-purple-600">Radiology Tests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $radiologyTest->test_name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-x-ray text-purple-600 mr-3"></i>
                            {{ $radiologyTest->test_name }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $radiologyTest->category->name ?? 'No category' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.radiology.tests.edit', $radiologyTest) }}" 
                           class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        <a href="{{ route('hms.radiology.tests.index') }}" 
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
                            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                <i class="fa fa-x-ray"></i>
                            </div>
                            <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $radiologyTest->test_name }}</h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                                {{ $radiologyTest->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fa fa-{{ $radiologyTest->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $radiologyTest->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-layer-group text-purple-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Category</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $radiologyTest->category->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-dollar-sign text-purple-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Price</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($radiologyTest->price, 2) }}
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
                            <i class="fa fa-info-circle text-purple-600 mr-2"></i> Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Test Name</label>
                                <div class="text-gray-900 dark:text-white font-semibold">{{ $radiologyTest->test_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Category</label>
                                <div class="text-gray-900 dark:text-white">{{ $radiologyTest->category->name ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Price</label>
                                <div class="text-gray-900 dark:text-white font-semibold text-lg">${{ number_format($radiologyTest->price, 2) }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $radiologyTest->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $radiologyTest->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($radiologyTest->description)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-file-alt text-purple-600 mr-2"></i> Description
                        </h3>
                        <div class="text-gray-900 dark:text-white">{{ $radiologyTest->description }}</div>
                    </div>
                    @endif

                    <!-- Preparation Instructions -->
                    @if($radiologyTest->preparation_instructions)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-info-circle text-purple-600 mr-2"></i> Preparation Instructions
                        </h3>
                        <div class="text-gray-900 dark:text-white whitespace-pre-line">{{ $radiologyTest->preparation_instructions }}</div>
                    </div>
                    @endif

                    <!-- Usage Statistics -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-chart-line text-purple-600 mr-2"></i> Usage Statistics
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-300">
                                    {{ $radiologyTest->requests()->count() }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Requests</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-300">
                                    {{ $radiologyTest->requests()->where('status', 'completed')->count() }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Completed</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                                    ${{ number_format($radiologyTest->requests()->sum('total_amount') ?? 0, 2) }}
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

