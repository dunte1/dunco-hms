<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Journal {{ $entry->entry_number }}
                </h1>
                <a href="{{ route('journal.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm">Back</a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">Date</dt><dd>{{ optional($entry->date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd>{{ $entry->status }}</dd></div>
                    <div class="col-span-2"><dt class="text-gray-500">Description</dt><dd>{{ $entry->description }}</dd></div>
                    <div><dt class="text-gray-500">Reference</dt><dd>{{ $entry->reference_type }} {{ $entry->reference_id }}</dd></div>
                    <div><dt class="text-gray-500">Posted at</dt><dd>{{ optional($entry->posted_at)->format('d M Y H:i') }}</dd></div>
                </dl>
            </div>

            @php $lines = $entry->lines ?? $entry->journalLines ?? collect(); @endphp
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-3">Lines</h2>
                @if($lines && $lines->count())
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-gray-500 border-b">
                            <th class="py-2">Account</th><th>Debit</th><th>Credit</th><th>Memo</th>
                        </tr></thead>
                        <tbody>
                            @php $totalDr = 0; $totalCr = 0; @endphp
                            @foreach($lines as $line)
                                @php
                                    $dr = (float) ($line->debit ?? $line->debit_amount ?? 0);
                                    $cr = (float) ($line->credit ?? $line->credit_amount ?? 0);
                                    $totalDr += $dr; $totalCr += $cr;
                                @endphp
                                <tr class="border-b">
                                    <td class="py-2">{{ optional($line->account)->name ?? $line->account_id ?? '—' }}</td>
                                    <td>{{ $dr ? number_format($dr, 2) : '—' }}</td>
                                    <td>{{ $cr ? number_format($cr, 2) : '—' }}</td>
                                    <td>{{ $line->memo ?? $line->description ?? '' }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-semibold">
                                <td class="py-2">Totals</td>
                                <td>{{ number_format($totalDr, 2) }}</td>
                                <td>{{ number_format($totalCr, 2) }}</td>
                                <td>
                                    @if(abs($totalDr - $totalCr) > 0.009)
                                        <span class="text-red-600">OUT OF BALANCE</span>
                                    @else
                                        <span class="text-green-600">Balanced</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-sm">No lines attached to this entry.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
