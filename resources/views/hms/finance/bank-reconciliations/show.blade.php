<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reconciliation #{{ $reconciliation->id }}</h1>
                <a href="{{ route('hms.finance.bank-reconciliations.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm">Back</a>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">Bank account</dt><dd>{{ optional($reconciliation->bankAccount)->name }} {{ optional($reconciliation->bankAccount)->account_number }}</dd></div>
                    <div><dt class="text-gray-500">Statement date</dt><dd>{{ optional($reconciliation->statement_date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-500">Statement balance</dt><dd>{{ number_format($reconciliation->statement_balance, 2) }}</dd></div>
                    <div><dt class="text-gray-500">Book balance</dt><dd>{{ number_format($reconciliation->book_balance, 2) }}</dd></div>
                    <div><dt class="text-gray-500">Difference</dt><dd class="{{ abs($reconciliation->difference) >= 0.01 ? 'text-red-600 font-semibold' : 'text-green-600' }}">{{ number_format($reconciliation->difference, 2) }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd>{{ $reconciliation->status }}</dd></div>
                    <div class="col-span-2"><dt class="text-gray-500">Reconciled by</dt><dd>{{ optional($reconciliation->reconciledBy)->name ?? '—' }}</dd></div>
                </dl>
                @if($reconciliation->status !== 'reconciled' && abs($reconciliation->difference) < 0.01)
                    <form method="POST" action="{{ route('hms.finance.bank-reconciliations.mark-reconciled', $reconciliation) }}" class="mt-4">
                        @csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg">Mark reconciled</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
