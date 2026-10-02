<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-user-nurse text-cyan-600 mr-2"></i> Nurse Allocations
                </h1>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                    <h2 class="font-semibold mb-3">New allocation</h2>
                    <form method="POST" action="{{ route('hms.nursing.allocations.store') }}" class="space-y-3">
                        @csrf
                        <div><label class="text-sm font-medium">Nurse user ID *</label>
                            <input type="number" name="nurse_user_id" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                        <div><label class="text-sm font-medium">Ward *</label>
                            <select name="ward_id" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                                <option value="">Select ward</option>
                                @foreach(($wards ?? collect()) as $ward)
                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                @endforeach
                            </select></div>
                        <div><label class="text-sm font-medium">Shift type *</label>
                            <select name="shift_type" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                                <option value="day">Day</option>
                                <option value="night">Night</option>
                            </select></div>
                        <div><label class="text-sm font-medium">Allocated date *</label>
                            <input type="date" name="allocated_date" required value="{{ now()->toDateString() }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="text-xs">Bed range start</label>
                                <input type="number" name="bed_range_start" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Bed range end</label>
                                <input type="number" name="bed_range_end" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                        </div>
                        <div><label class="text-sm font-medium">Patient count</label>
                            <input type="number" name="patient_count" min="0" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                        <div><label class="text-sm font-medium">Notes</label>
                            <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></textarea></div>
                        <button class="w-full px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Allocate</button>
                    </form>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 lg:col-span-2">
                    <h2 class="font-semibold mb-3">Current allocations</h2>
                    @php $list = $allocations ?? collect(); @endphp
                    @if($list && $list->count())
                        <table class="w-full text-sm">
                            <thead><tr class="text-left text-gray-500 border-b">
                                <th class="py-2">Nurse</th><th>Ward</th><th>Shift</th><th>Date</th><th>Beds</th><th>Patients</th><th>Status</th>
                            </tr></thead>
                            <tbody>
                                @foreach($list as $a)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $a->nurse_user_id }}</td>
                                        <td>{{ $a->ward_id }}</td>
                                        <td>{{ $a->shift_type }}</td>
                                        <td>{{ $a->allocated_date }}</td>
                                        <td>{{ $a->bed_range_start }}–{{ $a->bed_range_end }}</td>
                                        <td>{{ $a->patient_count }}</td>
                                        <td>{{ $a->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-sm">No allocations recorded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
