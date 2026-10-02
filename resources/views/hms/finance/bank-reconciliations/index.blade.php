<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-university text-indigo-600 mr-2"></i> Bank Reconciliation
                </h1>
                <a href="{{ route('hms.finance.bank-reconciliations.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">New reconciliation</a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Bank account</th>
                                <th class="px-4 py-3 text-left">Statement</th>
                                <th class="px-4 py-3 text-left">Book</th>
                                <th class="px-4 py-3 text-left">Difference</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($reconciliations as $rec)
                                <tr>
                                    <td class="px-4 py-3">{{ optional($rec->statement_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ optional($rec->bankAccount)->name }} {{ optional($rec->bankAccount)->account_number }}</td>
                                    <td class="px-4 py-3">{{ number_format($rec->statement_balance, 2) }}</td>
                                    <td class="px-4 py-3">{{ number_format($rec->book_balance, 2) }}</td>
                                    <td class="px-4 py-3 {{ abs($rec->difference) >= 0.01 ? 'text-red-600 font-semibold' : 'text-green-600' }}">{{ number_format($rec->difference, 2) }}</td>
                                    <td class="px-4 py-3">{{ $rec->status }}</td>
                                    <td class="px-4 py-3">
                                        <a class="text-blue-600 hover:underline" href="{{ route('hms.finance.bank-reconciliations.show', $rec) }}">View</a>
                                        @if($rec->status !== 'reconciled' && abs($rec->difference) < 0.01)
                                            <form method="POST" action="{{ route('hms.finance.bank-reconciliations.mark-reconciled', $rec) }}" class="inline ml-2">
                                                @csrf
                                                <button class="text-green-600 hover:underline">Mark reconciled</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No reconciliations yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $reconciliations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
