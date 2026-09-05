<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2"><a href="{{ route('hms.equipment.index') }}" class="hover:text-blue-600">Equipment</a><i class="fa fa-chevron-right text-xs"></i><span>{{ $equipment->name }}</span></div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-cogs text-teal-600 mr-3"></i>{{ $equipment->name }}</h1>
                </div>
                <a href="{{ route('hms.equipment.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Equipment Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Category:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($equipment->category) }}</span></div>
                            <div><span class="text-gray-500">Serial #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $equipment->serial_number }}</span></div>
                            <div><span class="text-gray-500">Department:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $equipment->department ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Manufacturer:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $equipment->manufacturer ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $equipment->status === 'operational' ? 'bg-green-100 text-green-800' : ($equipment->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst(str_replace('_', ' ', $equipment->status)) }}</span></div>
                            <div><span class="text-gray-500">Value:</span> <span class="font-medium text-gray-900 dark:text-white">${{ number_format($equipment->current_value ?? 0, 2) }}</span></div>
                        </div>
                    </div>
                    <!-- Maintenance Logs -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Maintenance History</h3>
                        <div class="space-y-3">
                            @forelse($equipment->maintenanceLogs as $log)
                                <div class="border-l-4 border-teal-400 pl-4 py-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $log->maintenance_type)) }}</span>
                                        <span class="text-xs text-gray-500">{{ $log->performed_at?->format('M d, Y') }}</span>
                                    </div>
                                    @if($log->description)<p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $log->description }}</p>@endif
                                    @if($log->cost)<span class="text-xs text-gray-500">Cost: ${{ number_format($log->cost, 2) }}</span>@endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No maintenance records</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <!-- Log Maintenance -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Log Maintenance</h3>
                        <form action="{{ route('hms.equipment.maintenance', $equipment) }}" method="POST" class="space-y-3">
                            @csrf
                            <div><label class="block text-xs text-gray-500 mb-1">Type *</label><select name="maintenance_type" required class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="preventive">Preventive</option><option value="corrective">Corrective</option><option value="calibration">Calibration</option><option value="emergency">Emergency</option></select></div>
                            <div><label class="block text-xs text-gray-500 mb-1">Description</label><textarea name="description" rows="2" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea></div>
                            <div><label class="block text-xs text-gray-500 mb-1">Cost ($)</label><input type="number" name="cost" step="0.01" min="0" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <div><label class="block text-xs text-gray-500 mb-1">Next Due Date</label><input type="date" name="next_due_date" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <button type="submit" class="w-full px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-semibold">Save Maintenance Log</button>
                        </form>
                    </div>
                    <!-- Status Update -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Status</h3>
                        <form action="{{ route('hms.equipment.status', $equipment) }}" method="POST" class="space-y-3">
                            @csrf @method('PUT')
                            <select name="status" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="operational" {{ $equipment->status === 'operational' ? 'selected' : '' }}>Operational</option>
                                <option value="maintenance" {{ $equipment->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="out_of_service" {{ $equipment->status === 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                                <option value="retired" {{ $equipment->status === 'retired' ? 'selected' : '' }}>Retired</option>
                            </select>
                            <button type="submit" class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
