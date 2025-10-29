<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-prescription text-green-600 mr-3"></i>
                        Prescriptions
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage patient prescriptions and medications</p>
                </div>
                <a href="{{ route('hms.pharmacy.prescriptions.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Prescription
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Prescriptions</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-prescription-bottle text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Today</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['today'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-day text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">This Week</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['this_week'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-week text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">This Month</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['this_month'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-alt text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.pharmacy.prescriptions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by patient name, number, diagnosis..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-user-md mr-1"></i> Doctor
                        </label>
                        <select name="doctor" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                            <option value="">All Doctors</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-calendar mr-1"></i> From Date
                        </label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-calendar mr-1"></i> To Date
                        </label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div class="flex items-end gap-2 md:col-span-5">
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Prescriptions List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($prescriptions as $prescription)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-start flex-1">
                                    <div class="flex-shrink-0 h-16 w-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <i class="fa fa-user text-2xl text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $prescription->patient->full_name ?? 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $prescription->patient->patient_no ?? '' }}
                                        </p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fa fa-user-md mr-1"></i> Dr. {{ $prescription->doctor ? $prescription->doctor->first_name . ' ' . $prescription->doctor->last_name : 'N/A' }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                <i class="fa fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($prescription->prescription_date)->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('hms.pharmacy.prescriptions.show', $prescription) }}" 
                                       class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('hms.pharmacy.prescriptions.edit', $prescription) }}" 
                                       class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition" title="Edit Prescription">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button onclick="window.print()" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition" title="Print">
                                        <i class="fa fa-print"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Diagnosis & Symptoms -->
                            @if($prescription->diagnosis || $prescription->symptoms)
                            <div class="mb-4">
                                @if($prescription->diagnosis)
                                <div class="mb-2">
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <i class="fa fa-stethoscope text-red-500 mr-1"></i> Diagnosis:
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $prescription->diagnosis }}</span>
                                </div>
                                @endif
                                @if($prescription->symptoms)
                                <div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <i class="fa fa-notes-medical text-orange-500 mr-1"></i> Symptoms:
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $prescription->symptoms }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Medicines -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fa fa-pills text-green-600 mr-2"></i> Prescribed Medications
                                </h4>
                                <div class="space-y-2">
                                    @forelse($prescription->items as $item)
                                        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fa fa-capsules text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $item->medicine->name ?? 'Unknown Medicine' }}
                                                            @if($item->medicine->strength)
                                                                <span class="text-gray-500">- {{ $item->medicine->strength }}</span>
                                                            @endif
                                                        </p>
                                                        <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-600 dark:text-gray-400">
                                                            <span><strong>Dosage:</strong> {{ $item->dosage }}</span>
                                                            <span><strong>Frequency:</strong> {{ $item->frequency }}</span>
                                                            <span><strong>Duration:</strong> {{ $item->duration_days }} days</span>
                                                            <span><strong>Qty:</strong> {{ $item->quantity }}</span>
                                                        </div>
                                                        @if($item->instructions)
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">
                                                            <i class="fa fa-info-circle mr-1"></i> {{ $item->instructions }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No medicines prescribed</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($prescription->notes)
                            <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <p class="text-xs font-semibold text-yellow-900 dark:text-yellow-300 mb-1">
                                    <i class="fa fa-sticky-note mr-1"></i> Additional Notes:
                                </p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400">{{ $prescription->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-prescription text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No prescriptions found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Start by creating a new prescription</p>
                        <a href="{{ route('hms.pharmacy.prescriptions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Create First Prescription
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($prescriptions->hasPages())
                <div class="mt-6">
                    {{ $prescriptions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
