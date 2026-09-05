<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-clock text-purple-600 mr-3"></i>
                        Operation Theatre
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Schedule and manage surgical operations</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.ot.schedule') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-calendar mr-2"></i> Schedule View
                    </a>
                    <a href="{{ route('hms.ot.rooms') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-door-open mr-2"></i> OT Rooms
                    </a>
                    <a href="{{ route('hms.ot.instruments') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-tools mr-2"></i> Instruments
                    </a>
                    <a href="{{ route('hms.ot.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-plus mr-2"></i> New Schedule
                    </a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg"><i class="fa fa-calendar-check text-purple-600 text-xl"></i></div>
                        <div class="ml-4"><p class="text-sm text-gray-600 dark:text-gray-400">Today Total</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['today_total'] }}</p></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg"><i class="fa fa-check-circle text-green-600 text-xl"></i></div>
                        <div class="ml-4"><p class="text-sm text-gray-600 dark:text-gray-400">Completed</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['today_completed'] }}</p></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg"><i class="fa fa-spinner text-blue-600 text-xl"></i></div>
                        <div class="ml-4"><p class="text-sm text-gray-600 dark:text-gray-400">In Progress</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['today_in_progress'] }}</p></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-teal-100 dark:bg-teal-900/30 rounded-lg"><i class="fa fa-door-open text-teal-600 text-xl"></i></div>
                        <div class="ml-4"><p class="text-sm text-gray-600 dark:text-gray-400">Rooms Available</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['rooms_available'] }}/{{ $stats['rooms_total'] }}</p></div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
                <form method="GET" action="{{ route('hms.ot.index') }}" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">All Status</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_preparation" {{ request('status') == 'in_preparation' ? 'selected' : '' }}>In Preparation</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Surgeon</label>
                        <select name="surgeon_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">All Surgeons</option>
                            @foreach($surgeons as $id => $name)
                                <option value="{{ $id }}" {{ request('surgeon_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room</label>
                        <select name="room_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">All Rooms</option>
                            @foreach($rooms as $id => $name)
                                <option value="{{ $id }}" {{ request('room_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <button type="submit" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>

            <!-- Schedules Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Schedule #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Procedure</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Surgeon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Risk</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($schedules as $schedule)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $schedule->schedule_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $schedule->patient->first_name }} {{ $schedule->patient->last_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $schedule->procedure_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $schedule->surgeon->first_name }} {{ $schedule->surgeon->last_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $schedule->otRoom->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $schedule->scheduled_date->format('M d, Y') }}<br>
                                        <span class="text-xs text-gray-500">{{ $schedule->scheduled_start }} - {{ $schedule->scheduled_end ?? 'TBD' }}</span>
                                    </td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->status_badge }}">{{ ucfirst(str_replace('_', ' ', $schedule->status)) }}</span></td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->risk_badge }}">{{ ucfirst($schedule->risk_level) }}</span></td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('hms.ot.show', $schedule) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('hms.ot.edit', $schedule) }}" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs"><i class="fa fa-edit"></i></a>
                                            @if($schedule->status === 'scheduled')
                                                <form action="{{ route('hms.ot.time-in', $schedule) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs"><i class="fa fa-play"></i></button>
                                                </form>
                                            @endif
                                            @if($schedule->status === 'in_progress')
                                                <form action="{{ route('hms.ot.time-out', $schedule) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs"><i class="fa fa-stop"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <i class="fa fa-clock text-5xl text-gray-400 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No OT schedules found</p>
                                        <a href="{{ route('hms.ot.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg"><i class="fa fa-plus mr-2"></i> Schedule Surgery</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($schedules->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $schedules->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
