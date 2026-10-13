<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <a href="{{ route('hms.opd.index') }}" class="hover:text-blue-600">OPD Visits</a>
                            <i class="fa fa-chevron-right text-xs"></i>
                            <span>Visit #{{ $opd->id }}</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-user-check text-blue-600 mr-3"></i>OPD Visit #{{ $opd->id }}</h1>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">{{ $opd->status_label }}</span>
                            <span class="text-gray-500 dark:text-gray-400">{{ $opd->patient->full_name ?? 'Patient' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 xl:items-end">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('hms.opd.edit', $opd) }}" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm"><i class="fa fa-edit mr-1"></i> Edit</a>
                            <a href="{{ route('hms.pharmacy.prescriptions.create') }}?patient_id={{ $opd->patient_id }}&opd_visit_id={{ $opd->id }}" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm"><i class="fa fa-prescription mr-1"></i> Prescription</a>
                            <a href="{{ route('hms.laboratory.requests.create') }}?patient_id={{ $opd->patient_id }}&opd_visit_id={{ $opd->id }}" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm"><i class="fa fa-flask mr-1"></i> Lab Request</a>
                            <a href="{{ route('hms.radiology.requests.create') }}?patient_id={{ $opd->patient_id }}" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm"><i class="fa fa-x-ray mr-1"></i> Radiology</a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(in_array($opd->status, ['registered', 'triaged'], true))
                                <form method="POST" action="{{ route('hms.opd.start-consultation', $opd) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg text-sm"><i class="fa fa-stethoscope mr-1"></i> Start Consultation</button>
                                </form>
                            @endif
                            @if(!in_array($opd->status, ['completed', 'finalized'], true))
                                <form method="POST" action="{{ route('hms.opd.finalize', $opd) }}" class="inline" onsubmit="return confirm('Finalize this encounter? It will be locked as completed.');">
                                    @csrf
                                    <button class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm"><i class="fa fa-lock mr-1"></i> Finalize Encounter</button>
                                </form>
                            @endif
                            <a href="{{ route('hms.opd.index') }}" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                        </div>
                    </div>
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
                    @if($opd->treatment_plan || $opd->prescription)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-clipboard text-blue-600 mr-2"></i>Treatment</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $opd->treatment_plan ?? $opd->prescription }}</p>
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
