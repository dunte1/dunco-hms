<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-pills text-green-600 mr-3"></i>
                    Pharmacy Management
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Complete pharmacy and medication management</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                @foreach($stats as $stat)
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">{{ $stat['label'] }}</p>
                                <p class="text-3xl font-bold mt-2">{{ $stat['value'] }}</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                <i class="fa fa-{{ $loop->index == 0 ? 'file-prescription' : ($loop->index == 1 ? 'check-circle' : ($loop->index == 2 ? 'pills' : 'exclamation-triangle')) }} text-2xl"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('hms.pharmacy.medicines.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <i class="fa fa-pills text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Medicines</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage medicines</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fa fa-file-prescription text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Prescriptions</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View prescriptions</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.pharmacy.medicine-categories.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <i class="fa fa-tags text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Categories</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Medicine categories</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.pharmacy.medicine-brands.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="p-3 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
                            <i class="fa fa-trademark text-cyan-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Brands</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Medicine brands</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                        <h3 class="text-lg font-bold text-white">Recent Prescriptions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentPrescriptions as $prescription)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $prescription->patient->full_name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Dr. {{ $prescription->doctor->full_name }}</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $prescription->status == 'dispensed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($prescription->status) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">No recent prescriptions</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
                        <h3 class="text-lg font-bold text-white">Low Stock Medicines</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($lowStockMedicines as $medicine)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $medicine->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $medicine->category->name ?? 'N/A' }}</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $medicine->stock_quantity }} left
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">All medicines in stock</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
