<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-user-times text-gray-600 mr-3"></i>Death Report #{{ $report->id }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.birth-death.edit-death', $report) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.birth-death.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Death Report Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->patient->full_name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Date of Death:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->date_of_death?->format('M d, Y h:i A') ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Cause of Death:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->cause_of_death ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $report->doctor->first_name ?? 'N/A' }} {{ $report->doctor->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($report->death_type ?? 'N/A') }}</span></div>
                    @if($report->notes)
                        <div class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->notes }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
