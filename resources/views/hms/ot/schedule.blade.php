<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-calendar-alt text-purple-600 mr-3"></i>
                        OT Schedule - {{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}
                    </h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.ot.schedule', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-chevron-left mr-1"></i> Previous</a>
                    <a href="{{ route('hms.ot.schedule', ['date' => now()->format('Y-m-d')]) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm">Today</a>
                    <a href="{{ route('hms.ot.schedule', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">Next <i class="fa fa-chevron-right ml-1"></i></a>
                </div>
            </div>

            <!-- Room Timelines -->
            <div class="space-y-4">
                @forelse($rooms as $room)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa fa-door-open text-white"></i>
                                <span class="text-white font-semibold">{{ $room->name }}</span>
                                <span class="text-purple-200 text-sm">{{ ucfirst($room->type) }} | {{ ucfirst($room->status) }}</span>
                            </div>
                            <span class="text-purple-200 text-sm">{{ $room->schedules->count() }} procedures</span>
                        </div>
                        <div class="p-4">
                            @php
                                $roomSchedules = $schedules->where('ot_room_id', $room->id);
                            @endphp
                            @if($roomSchedules->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($roomSchedules as $s)
                                        <a href="{{ route('hms.ot.show', $s) }}" class="block p-3 rounded-lg border-2 {{ $s->status === 'completed' ? 'border-gray-300 bg-gray-50 dark:bg-gray-700 dark:border-gray-600' : ($s->status === 'in_progress' ? 'border-green-400 bg-green-50 dark:bg-green-900/20' : 'border-blue-300 bg-blue-50 dark:bg-blue-900/20') }} hover:shadow-md transition">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-semibold {{ $s->status === 'completed' ? 'text-gray-600' : ($s->status === 'in_progress' ? 'text-green-700' : 'text-blue-700') }}">{{ $s->scheduled_start }} - {{ $s->scheduled_end ?? '?' }}</span>
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $s->status_badge }}">{{ str_replace('_', ' ', ucfirst($s->status)) }}</span>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $s->procedure_name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $s->patient->first_name }} {{ $s->patient->last_name }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Dr. {{ $s->surgeon->first_name }} {{ $s->surgeon->last_name }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No procedures scheduled for this room</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fa fa-door-open text-5xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No OT rooms configured</p>
                        <a href="{{ route('hms.ot.rooms') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg"><i class="fa fa-plus mr-2"></i> Add OT Room</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
