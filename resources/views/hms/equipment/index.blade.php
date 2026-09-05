<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa fa-cogs text-teal-600 mr-3"></i>Equipment Maintenance (CMMS)</h1>
                </div>
                <button onclick="document.getElementById('addEquipmentModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-plus mr-2"></i> Register Equipment</button>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4"><p class="text-sm text-gray-600">Total Equipment</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4"><p class="text-sm text-green-600">Operational</p><p class="text-2xl font-bold text-green-600">{{ $stats['operational'] }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4"><p class="text-sm text-yellow-600">In Maintenance</p><p class="text-2xl font-bold text-yellow-600">{{ $stats['maintenance'] }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4"><p class="text-sm text-red-600">Out of Service</p><p class="text-2xl font-bold text-red-600">{{ $stats['out_of_service'] }}</p></div>
            </div>
            <!-- Upcoming Maintenance -->
            @if($upcomingMaintenance->count() > 0)
                <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2"><i class="fa fa-wrench mr-1"></i> Upcoming Maintenance (Next 7 Days)</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($upcomingMaintenance as $eq)
                            <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 text-xs rounded-full">{{ $eq->name }} - {{ $eq->next_maintenance?->format('M d') }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- Equipment Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Next Maintenance</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($equipment as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($item->category) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $item->serial_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $item->department ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status === 'operational' ? 'bg-green-100 text-green-800' : ($item->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : ($item->status === 'out_of_service' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $item->next_maintenance?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-sm"><a href="{{ route('hms.equipment.show', $item) }}" class="px-2 py-1 bg-blue-600 text-white rounded text-xs"><i class="fa fa-eye"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No equipment registered</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add Equipment Modal -->
    <div id="addEquipmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Register Equipment</h3>
            <form action="{{ route('hms.equipment.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label><input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label><select name="category" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="diagnostic">Diagnostic</option><option value="therapeutic">Therapeutic</option><option value="surgical">Surgical</option><option value="life_support">Life Support</option><option value="laboratory">Laboratory</option><option value="other">Other</option></select></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Serial Number *</label><input type="text" name="serial_number" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label><input type="text" name="department" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manufacturer</label><input type="text" name="manufacturer" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model #</label><input type="text" name="model_number" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Purchase Date</label><input type="date" name="purchase_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warranty Expiry</label><input type="date" name="warranty_expiry" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label><input type="text" name="location" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Value ($)</label><input type="number" name="current_value" step="0.01" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">Save</button>
                    <button type="button" onclick="document.getElementById('addEquipmentModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
