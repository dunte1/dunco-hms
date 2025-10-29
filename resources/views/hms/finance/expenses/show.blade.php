<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Expense Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.finance.expenses.edit', $expense) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <a href="{{ route('hms.finance.expenses.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Expense Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Expense Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Expense Number</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $expense->expense_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                @if($expense->status == 'paid') bg-green-100 text-green-800
                                @elseif($expense->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($expense->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Expense Date</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $expense->expense_date->format('F d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount</label>
                            <p class="mt-1 text-2xl font-bold text-red-600">KES {{ number_format($expense->amount, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $expense->category->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Vendor Name</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $expense->vendor_name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reference Number</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $expense->reference_number ?? 'N/A' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $expense->description }}</p>
                        </div>

                        @if($expense->notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $expense->notes }}</p>
                        </div>
                        @endif

                        <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Recorded</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $expense->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.finance.expenses.edit', $expense) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                            Edit Expense
                        </a>
                        
                        <form action="{{ route('hms.finance.expenses.destroy', $expense) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this expense?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Delete Expense
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

