<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2"><a href="{{ route('hms.consent.index') }}" class="hover:text-blue-600">Consent</a><i class="fa fa-chevron-right text-xs"></i><span>New Consent</span></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-plus text-indigo-600 mr-3"></i>New Consent Form</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                <form method="POST" action="{{ route('hms.consent.store') }}" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient *</label><select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="">Select Patient</option>@foreach($patients as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor</label><select name="doctor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="">Select Doctor</option>@foreach($doctors as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Consent Type *</label><select name="consent_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="procedure">Procedure</option><option value="anesthesia">Anesthesia</option><option value="blood_transfusion">Blood Transfusion</option><option value="data_sharing">Data Sharing</option><option value="research">Research</option></select></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Procedure Name</label><input type="text" name="procedure_name" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label><textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Description of the procedure/treatment..."></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Risks Disclosed</label><textarea name="risks_disclosed" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alternatives Disclosed</label><textarea name="alternatives_disclosed" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label><textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea></div>
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Create</button>
                        <a href="{{ route('hms.consent.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
