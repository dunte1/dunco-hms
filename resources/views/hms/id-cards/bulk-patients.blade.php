<x-app-layout>
    <div class="py-6 print:p-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Patient ID Cards</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Printable cards for up to 200 patients</p>
                </div>
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Print All Cards</button>
            </div>

            @if(($patients ?? collect())->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-10 text-center text-gray-500">
                    No patients available to print ID cards.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($patients as $patient)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-16 h-20 bg-slate-200 dark:bg-slate-700 rounded flex items-center justify-center text-slate-500 flex-shrink-0">
                                    <i class="fa fa-user text-2xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs uppercase text-gray-500">Patient ID</div>
                                    <div class="font-bold text-lg truncate">{{ $patient->patient_no ?? '—' }}</div>
                                    <div class="text-sm truncate">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $patient->gender ? ucfirst($patient->gender) : '' }}
                                        {{ optional($patient->dob)->format('d M Y') ? ' · '.optional($patient->dob)->format('d M Y') : '' }}
                                    </div>
                                    <div class="mt-2 text-[10px] tracking-widest text-gray-600 dark:text-gray-300 font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded inline-block">
                                        {{ strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', ($patient->patient_no ?? '').($patient->first_name ?? '').($patient->last_name ?? '')), 0, 18)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
