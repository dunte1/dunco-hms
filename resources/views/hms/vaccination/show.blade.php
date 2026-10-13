<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('vaccination.index') }}" class="hover:text-emerald-600">Vaccination</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Vaccine #{{ $vaccine->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-syringe text-emerald-600 mr-3"></i>{{ $vaccine->name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('vaccination.vaccine-edit', $vaccine) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('vaccination.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('success') || session('status'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('success') ?: session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vaccine Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">Name:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $vaccine->name }}</span></div>
                        <div><span class="text-gray-500">Manufacturer:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $vaccine->manufacturer ?? 'N/A' }}</span></div>
                        <div><span class="text-gray-500">Stock:</span> <span class="font-medium {{ $vaccine->stock_quantity <= 5 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $vaccine->stock_quantity }}</span></div>
                        <div><span class="text-gray-500">Doses/Vial:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $vaccine->dose_count ?? 'N/A' }}</span></div>
                        <div><span class="text-gray-500">Expiry:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $vaccine->expiry_date?->format('M d, Y') ?? 'N/A' }}</span></div>
                        <div><span class="text-gray-500">Cost:</span> <span class="font-medium text-gray-900 dark:text-white">${{ number_format($vaccine->cost ?? 0, 2) }}</span></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Administrations</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($vaccine->vaccinationRecords as $record)
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->patient->full_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Dose {{ $record->dose_number }} · {{ $record->administered_at?->format('M d, Y') ?? '—' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No administrations recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
