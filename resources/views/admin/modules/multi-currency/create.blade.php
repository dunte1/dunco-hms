@extends('admin.layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Add New Currency</h2>
        <a href="{{ route('admin.modules.multi-currency.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Currencies
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.modules.multi-currency.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Currency Code <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="code" 
                       name="code" 
                       value="{{ old('code') }}"
                       maxlength="3"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="USD, EUR, KES, etc."
                       required>
                <p class="mt-1 text-sm text-gray-500">3-letter ISO 4217 currency code</p>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Currency Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="US Dollar, Euro, Kenyan Shilling, etc."
                       required>
            </div>

            <div>
                <label for="symbol" class="block text-sm font-medium text-gray-700 mb-2">
                    Currency Symbol <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="symbol" 
                       name="symbol" 
                       value="{{ old('symbol') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="$, €, KSh, etc."
                       required>
            </div>

            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                    Country/Region
                </label>
                <input type="text" 
                       id="country" 
                       name="country" 
                       value="{{ old('country') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="United States, European Union, Kenya, etc.">
            </div>

            <div>
                <label for="exchange_rate" class="block text-sm font-medium text-gray-700 mb-2">
                    Exchange Rate <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="exchange_rate" 
                       name="exchange_rate" 
                       value="{{ old('exchange_rate', 1.000000) }}"
                       step="0.000001"
                       min="0.000001"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       required>
                <p class="mt-1 text-sm text-gray-500">Rate relative to base currency</p>
            </div>

            <div>
                <label for="decimal_places" class="block text-sm font-medium text-gray-700 mb-2">
                    Decimal Places <span class="text-red-500">*</span>
                </label>
                <select id="decimal_places" 
                        name="decimal_places" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="0" {{ old('decimal_places') == 0 ? 'selected' : '' }}>0 (Whole numbers)</option>
                    <option value="1" {{ old('decimal_places') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('decimal_places', 2) == 2 ? 'selected' : '' }}>2 (Standard)</option>
                    <option value="3" {{ old('decimal_places') == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('decimal_places') == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('decimal_places') == 5 ? 'selected' : '' }}>5</option>
                    <option value="6" {{ old('decimal_places') == 6 ? 'selected' : '' }}>6</option>
                </select>
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                    Symbol Position <span class="text-red-500">*</span>
                </label>
                <select id="position" 
                        name="position" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="before" {{ old('position', 'before') == 'before' ? 'selected' : '' }}>Before amount ($100)</option>
                    <option value="after" {{ old('position') == 'after' ? 'selected' : '' }}>After amount (100 $)</option>
                </select>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Optional description about this currency">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_base_currency" 
                       name="is_base_currency" 
                       value="1"
                       {{ old('is_base_currency') ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_base_currency" class="ml-2 block text-sm text-gray-900">
                    Set as base currency
                </label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.modules.multi-currency.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                Create Currency
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('is_base_currency').addEventListener('change', function() {
    const exchangeRateInput = document.getElementById('exchange_rate');
    if (this.checked) {
        exchangeRateInput.value = '1.000000';
        exchangeRateInput.readOnly = true;
    } else {
        exchangeRateInput.readOnly = false;
    }
});

document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endsection

