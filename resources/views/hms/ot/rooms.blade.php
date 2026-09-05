<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-door-open text-purple-600 mr-3"></i>
                        OT Rooms
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage operation theatre rooms</p>
                </div>
                <button onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Room
                </button>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($rooms as $room)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $room->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($room->type) }} @if($room->floor) | Floor {{ $room->floor }} @endif</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : ($room->status === 'occupied' ? 'bg-red-100 text-red-800' : ($room->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">{{ ucfirst($room->status) }}</span>
                            </div>
                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex items-center text-gray-700 dark:text-gray-300"><i class="fa fa-layer-group text-gray-400 mr-2 w-5"></i>Capacity: {{ $room->capacity }}</div>
                                @if($room->equipment_notes)
                                    <div class="text-gray-600 dark:text-gray-400 text-xs"><i class="fa fa-tools text-gray-400 mr-2"></i>{{ Str::limit($room->equipment_notes, 80) }}</div>
                                @endif
                                <div class="text-gray-500 text-xs">{{ $room->schedules_count ?? 0 }} total procedures</div>
                            </div>
                            <form action="{{ route('hms.ot.rooms.update', $room) }}" method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <select name="status" class="flex-1 text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="cleaning" {{ $room->status === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                </select>
                                <button class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded-lg"><i class="fa fa-save"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-door-open text-6xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No OT rooms configured</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add rooms to start scheduling surgeries</p>
                    </div>
                @endforelse
            </div>

            @if($rooms->hasPages())
                <div class="mt-6">{{ $rooms->links() }}</div>
            @endif
        </div>
    </div>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-plus text-purple-600 mr-2"></i>Add OT Room</h3>
                <form action="{{ route('hms.ot.rooms.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g., OT-1, Cardiac Suite">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="general">General</option>
                                <option value="cardiac">Cardiac</option>
                                <option value="neuro">Neuro</option>
                                <option value="orthopedic">Orthopedic</option>
                                <option value="emergency">Emergency</option>
                                <option value="pediatric">Pediatric</option>
                                <option value="ophthalmic">Ophthalmic</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Floor</label>
                            <input type="text" name="floor" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g., 2nd Floor">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Equipment Notes</label>
                        <textarea name="equipment_notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Available equipment in this room..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity</label>
                        <input type="number" name="capacity" value="1" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex gap-4 pt-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i>Save</button>
                        <button type="button" onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
