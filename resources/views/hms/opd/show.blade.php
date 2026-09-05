<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.opd.index') }}" class="hover:text-blue-600">OPD Visits</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Visit #{{ $opd->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-user-check text-blue-600 mr-3"></i>OPD Visit #{{ $opd->id }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.opd.edit', $opd) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.opd.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Visit Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $opd->patient->first_name }} {{ $opd->patient->last_name }}</span></div>
                            <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $opd->doctor->first_name ?? '' }} {{ $opd->doctor->last_name ?? '' }}</span></div>
                            <div><span class="text-gray-500">Visit Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $opd->visit_date?->format('M d, Y') ?? 'N/A' }}</span></div>
                            @if($opd->chief_complaint)
                                <div class="md:col-span-2"><span class="text-gray-500">Chief Complaint:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $opd->chief_complaint }}</span></div>
                            @endif
                        </div>
                    </div>
                    @if($opd->diagnosis)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-stethoscope text-blue-600 mr-2"></i>Diagnosis</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $opd->diagnosis }}</p>
                        </div>
                    @endif
                    @if($opd->treatment)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-clipboard text-blue-600 mr-2"></i>Treatment</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $opd->treatment }}</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Patient Info</h3>
                        <div class="text-sm space-y-2">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $opd->patient->first_name }} {{ $opd->patient->last_name }}</p>
                            @if($opd->patient->phone)<p class="text-gray-600 dark:text-gray-400"><i class="fa fa-phone mr-1"></i>{{ $opd->patient->phone }}</p>@endif
                            @if($opd->patient->email)<p class="text-gray-600 dark:text-gray-400"><i class="fa fa-envelope mr-1"></i>{{ $opd->patient->email }}</p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
