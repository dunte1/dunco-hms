<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.diagnosis.patient-diagnoses') }}" class="hover:text-indigo-600">Patient Diagnoses</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Diagnosis #{{ $diagnosis->id }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-file-medical-alt text-indigo-600 mr-3"></i>Diagnosis Details</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.diagnosis.patient-diagnoses.edit', $diagnosis) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.diagnosis.patient-diagnoses') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Diagnosis Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $diagnosis->patient->full_name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $diagnosis->doctor->first_name ?? 'N/A' }} {{ $diagnosis->doctor->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $diagnosis->diagnosis_date?->format('M d, Y') ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $diagnosis->status === 'active' ? 'bg-blue-100 text-blue-800' : ($diagnosis->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800') }}">{{ ucfirst($diagnosis->status) }}</span></div>
                    <div class="md:col-span-2"><span class="text-gray-500">Diagnosis:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $diagnosis->diagnosis }}</span></div>
                    @if($diagnosis->symptoms)
                        <div class="md:col-span-2"><span class="text-gray-500">Symptoms:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $diagnosis->symptoms }}</span></div>
                    @endif
                    @if($diagnosis->treatment_plan)
                        <div class="md:col-span-2"><span class="text-gray-500">Treatment Plan:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $diagnosis->treatment_plan }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
