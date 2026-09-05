<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2"><a href="{{ route('hms.vaccination.index') }}" class="hover:text-blue-600">Vaccination</a><i class="fa fa-chevron-right text-xs"></i><span>Administer</span></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-syringe text-emerald-600 mr-3"></i>Administer Vaccination</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
                <form method="POST" action="{{ route('hms.vaccination.administer-store') }}" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient *</label><select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="">Select Patient</option>@foreach($patients as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vaccine *</label><select name="vaccine_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="">Select Vaccine</option>@foreach($vaccines as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dose Number *</label><input type="number" name="dose_number" value="1" min="1" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Injection Site</label><select name="site" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="left_arm">Left Arm</option><option value="right_arm">Right Arm</option><option value="left_thigh">Left Thigh</option><option value="right_thigh">Right Thigh</option></select></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batch Number</label><input type="text" name="batch_number" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Next Dose Date</label><input type="date" name="next_dose_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reaction Notes</label><textarea name="reaction_notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea></div>
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Record Vaccination</button>
                        <a href="{{ route('hms.vaccination.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
