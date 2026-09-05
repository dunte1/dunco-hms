<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-baby text-pink-600 mr-3"></i>Birth Report #{{ $report->id }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.birth-death.edit-birth', $report) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.birth-death.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Birth Report Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Child Name:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->child_name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Date of Birth:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->date_of_birth?->format('M d, Y h:i A') ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Gender:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($report->gender ?? 'N/A') }}</span></div>
                    <div><span class="text-gray-500">Weight:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->weight ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Mother:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->mother_name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Father:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->father_name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $report->doctor->first_name ?? 'N/A' }} {{ $report->doctor->last_name ?? '' }}</span></div>
                    @if($report->notes)
                        <div class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $report->notes }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
