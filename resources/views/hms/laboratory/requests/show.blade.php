<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.laboratory.requests.index') }}" class="hover:text-blue-600">Lab Requests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $labRequest->request_number }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-flask text-blue-600 mr-3"></i>
                    Lab Request Details
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $labRequest->request_number }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Patient & Request Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Request Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Request Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Request Number</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $labRequest->request_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Request Date</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($labRequest->request_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Patient</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $labRequest->patient->full_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Requesting Doctor</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">Dr. {{ $labRequest->doctor->first_name ?? 'N/A' }} {{ $labRequest->doctor->last_name ?? '' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $labRequest->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($labRequest->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($labRequest->status ?? 'pending') }}
                                    </span>
                                </div>
                            </div>

                            @if($labRequest->clinical_notes)
                                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Clinical Notes</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $labRequest->clinical_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lab Tests -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Requested Tests</h3>
                            <div class="space-y-3">
                                @foreach($labRequest->items as $item)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $item->labTest->test_name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->labTest->category->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-blue-600 dark:text-blue-400">${{ number_format($item->labTest->price, 2) }}</p>
                                            <span class="text-xs px-2 py-1 rounded-full
                                                {{ $item->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($item->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($item->status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h3>
                        <div class="space-y-3">
                            <button class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                <i class="fa fa-print mr-2"></i> Print Request
                            </button>
                            <button class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                <i class="fa fa-file-medical mr-2"></i> Add Results
                            </button>
                            <button class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                                <i class="fa fa-edit mr-2"></i> Edit Request
                            </button>
                            <a href="{{ route('hms.laboratory.requests.index') }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition text-center">
                                <i class="fa fa-arrow-left mr-2"></i> Back to List
                            </a>
                        </div>

                        <!-- Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Tests:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $labRequest->items->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Amount:</span>
                                    <span class="font-semibold text-blue-600 dark:text-blue-400">${{ number_format($labRequest->items->sum(fn($item) => $item->labTest->price), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

