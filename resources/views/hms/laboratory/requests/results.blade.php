<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Lab Results Entry</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Request {{ $labRequest->request_number }} ·
                        {{ $labRequest->patient->full_name ?? ($labRequest->patient->first_name ?? 'Patient') }} ·
                        Status: {{ $labRequest->status }}
                    </p>
                </div>
                <a href="{{ route('hms.laboratory.requests.show', $labRequest) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Request
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('hms.laboratory.requests.results.store', $labRequest) }}" class="space-y-4">
                @csrf

                @forelse(($labRequest->items ?? []) as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-3">
                        <div class="font-semibold text-gray-900 dark:text-white">
                            {{ $item->labTest->name ?? ('Test #' . $item->id) }}
                            <span class="ml-2 text-xs font-normal text-gray-500">Current: {{ $item->result_value ?? '—' }}</span>
                        </div>
                        <input type="hidden" name="results[{{ $item->id }}][result_value]" value="{{ old('results.'.$item->id.'.result_value', $item->result_value) }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Result *</label>
                                <input type="text" name="results[{{ $item->id }}][result_value]"
                                       value="{{ old('results.'.$item->id.'.result_value', $item->result_value) }}"
                                       required
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Unit</label>
                                <input type="text" name="results[{{ $item->id }}][unit]"
                                       value="{{ old('results.'.$item->id.'.unit', $item->unit) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Notes</label>
                                <input type="text" name="results[{{ $item->id }}][result_notes]"
                                       value="{{ old('results.'.$item->id.'.result_notes', $item->result_notes) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center text-gray-500">
                        No test items on this request yet.
                    </div>
                @endforelse

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Overall results notes</label>
                    <textarea name="results_notes" rows="3"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2"
                              placeholder="Interpretation, critical values, follow-up...">{{ old('results_notes', $labRequest->results_notes) }}</textarea>
                </div>

                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold">
                    <i class="fa fa-save mr-2"></i> Save Results &amp; Notify Doctor
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
