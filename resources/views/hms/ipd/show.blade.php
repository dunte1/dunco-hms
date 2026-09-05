<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.ipd.index') }}" class="hover:text-blue-600">IPD Admissions</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Admission #{{ $ipd->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-bed text-blue-600 mr-3"></i>IPD Admission #{{ $ipd->id }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.ipd.edit', $ipd) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.ipd.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Admission Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ipd->patient->first_name }} {{ $ipd->patient->last_name }}</span></div>
                            <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $ipd->doctor->first_name ?? '' }} {{ $ipd->doctor->last_name ?? '' }}</span></div>
                            <div><span class="text-gray-500">Admission Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ipd->admission_date?->format('M d, Y') ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Bed:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ipd->bed?->bed_number ?? 'Not assigned' }}</span></div>
                            <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $ipd->status === 'discharged' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($ipd->status ?? 'admitted') }}</span></div>
                            @if($ipd->discharge_date)
                                <div><span class="text-gray-500">Discharge Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ipd->discharge_date->format('M d, Y') }}</span></div>
                            @endif
                        </div>
                    </div>
                    @if($ipd->diagnosis)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-stethoscope text-blue-600 mr-2"></i>Diagnosis</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $ipd->diagnosis }}</p>
                        </div>
                    @endif
                    @if($ipd->treatment_plan)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-clipboard-list text-blue-600 mr-2"></i>Treatment Plan</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $ipd->treatment_plan }}</p>
                        </div>
                    @endif
                    @if($ipd->notes)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-sticky-note text-blue-600 mr-2"></i>Notes</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $ipd->notes }}</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Patient Info</h3>
                        <div class="text-sm space-y-2">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $ipd->patient->first_name }} {{ $ipd->patient->last_name }}</p>
                            @if($ipd->patient->phone)<p class="text-gray-600 dark:text-gray-400"><i class="fa fa-phone mr-1"></i>{{ $ipd->patient->phone }}</p>@endif
                            @if($ipd->patient->email)<p class="text-gray-600 dark:text-gray-400"><i class="fa fa-envelope mr-1"></i>{{ $ipd->patient->email }}</p>@endif
                        </div>
                    </div>
                    @if($ipd->bed)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Bed Info</h3>
                            <div class="text-sm space-y-2">
                                <p class="font-medium text-gray-900 dark:text-white">Bed {{ $ipd->bed->bed_number }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ $ipd->bed->ward_name }}</p>
                                @if($ipd->bed->bedType)<p class="text-gray-600 dark:text-gray-400">{{ $ipd->bed->bedType->name }} - ${{ number_format($ipd->bed->bedType->charge_per_day, 2) }}/day</p>@endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
