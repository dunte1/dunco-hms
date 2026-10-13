<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.discharge-summary.index') }}" class="hover:text-blue-600">Discharge Summaries</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Summary #{{ $discharge->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-file-medical text-blue-600 mr-3"></i>Discharge Summary</h1>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('hms.discharge-summary.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                    <a href="{{ route('hms.discharge-summary.pdf', $discharge) }}"
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium"
                       title="Download PDF">
                        <i class="fa fa-file-pdf mr-2"></i> Download PDF
                    </a>
                    <button type="button" onclick="window.print()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium no-print"
                            title="Print this page">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 space-y-6 print:shadow-none print:rounded-none print:p-0">
                <div class="grid grid-cols-2 gap-4 text-sm print:text-black">
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $discharge->patient->first_name ?? '' }} {{ $discharge->patient->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Patient No:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $discharge->patient->patient_no ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $discharge->doctor->first_name ?? '' }} {{ $discharge->doctor->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Bed/Ward:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $discharge->bed->ward_name ?? '' }} {{ $discharge->bed->bed_number ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Admission Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $discharge->admission_date?->format('M d, Y') ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Discharge Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $discharge->discharge_date?->format('M d, Y') ?? 'N/A' }}</span></div>
                    <div class="md:col-span-2"><span class="text-gray-500">Diagnosis:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $discharge->diagnosis ?? 'N/A' }}</span></div>
                </div>

                @if($discharge->treatment_plan ?? $discharge->treatment_given ?? null)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Treatment Given</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $discharge->treatment_plan ?? $discharge->treatment_given }}</p>
                    </div>
                @endif

                @if($discharge->condition_at_discharge ?? null)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Condition at Discharge</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $discharge->condition_at_discharge }}</p>
                    </div>
                @endif

                @if($discharge->discharge_advice ?? null)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Discharge Advice</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $discharge->discharge_advice }}</p>
                    </div>
                @endif

                @if($discharge->follow_up_instructions ?? null)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Follow-up Instructions</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $discharge->follow_up_instructions }}</p>
                    </div>
                @endif

                @if($discharge->medications_on_discharge ?? null)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Medications on Discharge</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $discharge->medications_on_discharge }}</p>
                    </div>
                @endif

                @if($discharge->notes ?? null)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Additional Notes</h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $discharge->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
