<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">New Bank Reconciliation</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form method="POST" action="{{ route('hms.finance.bank-reconciliations.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Bank account *</label>
                        <select name="bank_account_id" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                            <option value="">Select account</option>
                            @foreach($bankAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} — {{ $acc->bank_name }} ({{ $acc->account_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Statement date *</label>
                        <input type="date" name="statement_date" required value="{{ now()->toDateString() }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Statement balance *</label>
                            <input type="number" step="0.01" name="statement_balance" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Book balance *</label>
                            <input type="number" step="0.01" name="book_balance" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
                        <a href="{{ route('hms.finance.bank-reconciliations.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
