<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-id-card text-emerald-600 mr-3"></i> Register RFID Tag
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Assign an RFID tag to a patient or staff member</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('hms.rfid.store') }}" class="max-w-2xl">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">RFID Tag Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tag Number *</label>
                            <input type="text" name="tag_number" value="{{ old('tag_number') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="e.g., RFID-001-ABC">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign To Type *</label>
                            <select name="assignable_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select type</option>
                                <option value="patient" {{ old('assignable_type') == 'patient' ? 'selected' : '' }}>Patient</option>
                                <option value="employee" {{ old('assignable_type') == 'employee' ? 'selected' : '' }}>Staff Member</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign To ID *</label>
                            <input type="number" name="assignable_id" value="{{ old('assignable_id') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Patient or Employee ID">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tag Type</label>
                            <select name="tag_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="wristband" {{ old('tag_type') == 'wristband' ? 'selected' : '' }}>Wristband</option>
                                <option value="card" {{ old('tag_type') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="sticker" {{ old('tag_type') == 'sticker' ? 'selected' : '' }}>Sticker</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea name="notes" rows="2"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa-solid fa-plus mr-2"></i> Register Tag
                    </button>
                    <a href="{{ route('hms.rfid.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
