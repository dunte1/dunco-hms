@extends('admin.layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Multi-Currency Management</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.modules.multi-currency.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Add Currency
            </a>
            <button onclick="updateExchangeRates()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-sync-alt mr-2"></i>Update Exchange Rates
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exchange Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($currencies as $currency)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $currency->name }}</div>
                                <div class="text-sm text-gray-500">{{ $currency->country }}</div>
                            </div>
                            @if($currency->is_base_currency)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Base
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                        {{ $currency->code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $currency->symbol }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($currency->is_base_currency)
                            <span class="text-gray-500">1.000000 (Base)</span>
                        @else
                            {{ number_format($currency->exchange_rate, 6) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($currency->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $currency->last_updated ? $currency->last_updated->format('M d, Y H:i') : 'Never' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.modules.multi-currency.show', $currency) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.modules.multi-currency.edit', $currency) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$currency->is_base_currency)
                                <form action="{{ route('admin.modules.multi-currency.set-base', $currency) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-yellow-600 hover:text-yellow-900"
                                            onclick="return confirm('Set {{ $currency->name }} as base currency?')">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.modules.multi-currency.destroy', $currency) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this currency?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No currencies found. <a href="{{ route('admin.modules.multi-currency.create') }}" class="text-blue-600 hover:text-blue-800">Add your first currency</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($currencies->hasPages())
        <div class="mt-6">
            {{ $currencies->links() }}
        </div>
    @endif
</div>

<!-- Exchange Rate Update Modal -->
<div id="exchangeRateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Exchange Rates</h3>
                <form id="exchangeRateForm" method="POST" action="{{ route('admin.modules.multi-currency.update-exchange-rates') }}">
                    @csrf
                    <div class="space-y-4">
                        @foreach($currencies->where('is_active', true)->where('is_base_currency', false) as $currency)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $currency->name }} ({{ $currency->code }})</label>
                            <input type="number" 
                                   name="exchange_rates[{{ $currency->id }}]" 
                                   value="{{ $currency->exchange_rate }}"
                                   step="0.000001"
                                   min="0.000001"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeExchangeRateModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                            Update Rates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateExchangeRates() {
    document.getElementById('exchangeRateModal').classList.remove('hidden');
}

function closeExchangeRateModal() {
    document.getElementById('exchangeRateModal').classList.add('hidden');
}
</script>
@endsection

