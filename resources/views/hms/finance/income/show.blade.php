<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Income Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.finance.income.edit', $income) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <a href="{{ route('hms.finance.income.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Income Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Income Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Income Number</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $income->income_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount</label>
                            <p class="mt-1 text-2xl font-bold text-green-600">KES {{ number_format($income->amount, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Income Date</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $income->income_date->format('F d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                            <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                @if($income->income_category == 'patient_services') bg-blue-100 text-blue-800
                                @elseif($income->income_category == 'pharmacy_sales') bg-green-100 text-green-800
                                @elseif($income->income_category == 'lab_tests') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $income->income_category)) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Account</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $income->account->account_name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Patient</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                @if($income->patient)
                                    {{ $income->patient->first_name }} {{ $income->patient->last_name }}
                                    <span class="text-sm text-gray-500">({{ $income->patient->patient_no }})</span>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Source</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $income->source ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Reference Number</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $income->reference_number ?? 'N/A' }}</p>
                        </div>

                        @if($income->is_recurring)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Recurring</label>
                            <p class="mt-1">
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($income->recurring_frequency ?? 'N/A') }}
                                </span>
                            </p>
                        </div>
                        @endif

                        @if($income->description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $income->description }}</p>
                        </div>
                        @endif

                        @if($income->notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $income->notes }}</p>
                        </div>
                        @endif

                        <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Recorded By</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $income->recorder->name ?? 'System' }} on {{ $income->created_at->format('F d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.finance.income.edit', $income) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                            Edit Income
                        </a>
                        
                        <form action="{{ route('hms.finance.income.destroy', $income) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this income record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Delete Income
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

