<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.telemedicine.index') }}" class="hover:text-blue-600">Telemedicine</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Session Details</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-video text-teal-600 mr-3"></i>Telemedicine Session</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.telemedicine.edit', $session) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    @if($session->status === 'scheduled')
                        <form action="{{ route('hms.telemedicine.start', $session) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-play mr-1"></i> Start</button>
                        </form>
                    @endif
                    @if($session->status === 'in_progress')
                        <form action="{{ route('hms.telemedicine.end', $session) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"><i class="fa fa-stop mr-1"></i> End</button>
                        </form>
                    @endif
                    <a href="{{ route('hms.telemedicine.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Session Details</h3>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $session->status === 'completed' ? 'bg-green-100 text-green-800' : ($session->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst(str_replace('_', ' ', $session->status)) }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $session->patient->first_name ?? '' }} {{ $session->patient->last_name ?? '' }}</span></div>
                            <div><span class="text-gray-500">Doctor:</span> <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $session->doctor->first_name ?? '' }} {{ $session->doctor->last_name ?? '' }}</span></div>
                            <div><span class="text-gray-500">Scheduled:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $session->scheduled_date?->format('M d, Y') }} at {{ $session->scheduled_time }}</span></div>
                            <div><span class="text-gray-500">Platform:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $session->platform ?? 'N/A')) }}</span></div>
                            @if($session->started_at)
                                <div><span class="text-gray-500">Started:</span> <span class="font-medium text-green-600">{{ $session->started_at->format('H:i') }}</span></div>
                            @endif
                            @if($session->ended_at)
                                <div><span class="text-gray-500">Ended:</span> <span class="font-medium text-red-600">{{ $session->ended_at->format('H:i') }}</span></div>
                            @endif
                        </div>
                    </div>

                    @if($session->reason)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Reason for Consultation</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $session->reason }}</p>
                        </div>
                    @endif

                    @if($session->notes)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Session Notes</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $session->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    @if($session->meeting_link)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Meeting Link</h3>
                            <a href="{{ $session->meeting_link }}" target="_blank" class="block w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white text-center rounded-lg font-semibold">
                                <i class="fa fa-video mr-2"></i> Join Meeting
                            </a>
                            <p class="text-xs text-gray-500 mt-2 text-center break-all">{{ $session->meeting_link }}</p>
                        </div>
                    @endif

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Patient Info</h3>
                        <div class="text-sm space-y-2">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $session->patient->first_name ?? '' }} {{ $session->patient->last_name ?? '' }}</p>
                            @if($session->patient->phone)<p class="text-gray-600 dark:text-gray-400"><i class="fa fa-phone mr-1"></i>{{ $session->patient->phone }}</p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
