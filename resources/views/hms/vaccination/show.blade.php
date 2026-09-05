<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.vaccination.index') }}" class="hover:text-emerald-600">Vaccination</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Record #{{ $record->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-syringe text-emerald-600 mr-3"></i>Vaccination Record</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.vaccination.edit', $record) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.vaccination.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vaccination Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->patient->first_name ?? 'N/A' }} {{ $record->patient->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Vaccine:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->vaccine->name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Dose #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->dose_number }}</span></div>
                    <div><span class="text-gray-500">Administered:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->administered_at?->format('M d, Y h:i A') ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Administered By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->administered_by ?? 'N/A' }}</span></div>
                    @if($record->lot_number)
                        <div><span class="text-gray-500">Lot #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->lot_number }}</span></div>
                    @endif
                    @if($record->notes)
                        <div class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->notes }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
