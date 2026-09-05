<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2"><a href="{{ route('hms.consent.index') }}" class="hover:text-blue-600">Consent</a><i class="fa fa-chevron-right text-xs"></i><span>Details</span></div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-file-signature text-indigo-600 mr-3"></i>Consent Form</h1>
                </div>
                <div class="flex gap-3">
                    @if($consent->status === 'pending')
                        <form action="{{ route('hms.consent.sign', $consent) }}" method="POST">@csrf<button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-check mr-1"></i> Sign Consent</button></form>
                    @endif
                    <a href="{{ route('hms.consent.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 space-y-6">
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $consent->status === 'signed' ? 'bg-green-100 text-green-800' : ($consent->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($consent->status) }}</span>
                    <span class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $consent->consent_type)) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $consent->patient->first_name ?? '' }} {{ $consent->patient->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $consent->doctor->first_name ?? '' }} {{ $consent->doctor->last_name ?? '' }}</span></div>
                    @if($consent->procedure_name)<div><span class="text-gray-500">Procedure:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $consent->procedure_name }}</span></div>@endif
                    @if($consent->signed_at)<div><span class="text-gray-500">Signed At:</span> <span class="font-medium text-green-600">{{ $consent->signed_at->format('M d, Y H:i') }}</span></div>@endif
                </div>
                @if($consent->description)<div><span class="text-xs text-gray-500 uppercase">Description</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $consent->description }}</p></div>@endif
                @if($consent->risks_disclosed)<div><span class="text-xs text-gray-500 uppercase">Risks</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $consent->risks_disclosed }}</p></div>@endif
                @if($consent->alternatives_disclosed)<div><span class="text-xs text-gray-500 uppercase">Alternatives</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $consent->alternatives_disclosed }}</p></div>@endif
            </div>
        </div>
    </div>
</x-app-layout>
