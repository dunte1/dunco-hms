<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa fa-syringe text-emerald-600 mr-3"></i>Vaccination Management</h1>
                </div>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('addVaccineModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-plus mr-2"></i> Add Vaccine</button>
                    <a href="{{ route('hms.vaccination.administer') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-syringe mr-2"></i> Administer</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Vaccine Stock -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h2 class="text-lg font-semibold text-gray-900 dark:text-white">Vaccine Stock</h2></div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th></tr></thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($vaccines as $vaccine)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $vaccine->name }}</td>
                                    <td class="px-4 py-3 text-sm {{ $vaccine->stock_quantity <= 5 ? 'text-red-600 font-bold' : 'text-gray-700' }}">{{ $vaccine->stock_quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $vaccine->expiry_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">${{ number_format($vaccine->cost ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No vaccines registered</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Recent Administrations -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Administrations</h2></div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                        @forelse($recentRecords as $record)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div><p class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->patient->first_name ?? '' }} {{ $record->patient->last_name ?? '' }}</p><p class="text-xs text-gray-500">{{ $record->vaccine->name ?? '' }} - Dose {{ $record->dose_number }}</p></div>
                                    <span class="text-xs text-gray-500">{{ $record->administered_at?->format('M d, H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500">No vaccination records yet</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Vaccine Modal -->
    <div id="addVaccineModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Add Vaccine</h3>
            <form action="{{ route('hms.vaccination.vaccine-store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label><input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manufacturer</label><input type="text" name="manufacturer" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock</label><input type="number" name="stock_quantity" value="0" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doses/Vial</label><input type="number" name="dose_count" value="1" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date</label><input type="date" name="expiry_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cost ($)</label><input type="number" name="cost" step="0.01" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Save</button>
                    <button type="button" onclick="document.getElementById('addVaccineModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
