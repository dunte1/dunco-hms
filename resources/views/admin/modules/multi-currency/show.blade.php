@extends('admin.layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Currency Details: {{ $currency->name }}</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.modules.multi-currency.edit', $currency) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.modules.multi-currency.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Currencies
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Currency Code</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $currency->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Currency Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $currency->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Symbol</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $currency->symbol }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Country/Region</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $currency->country ?? 'Not specified' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Exchange Rate Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Exchange Rate</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($currency->is_base_currency)
                                <span class="text-gray-500">1.000000 (Base Currency)</span>
                            @else
                                {{ number_format($currency->exchange_rate, 6) }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $currency->last_updated ? $currency->last_updated->format('M d, Y H:i') : 'Never' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Base Currency</dt>
                        <dd class="mt-1">
                            @if($currency->is_base_currency)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    No
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Formatting Settings</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Decimal Places</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $currency->decimal_places }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Symbol Position</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $currency->position }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Example Format</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">
                            {{ $currency->formatAmount(1234.56) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($currency->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $currency->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Modified</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $currency->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            @if($currency->description)
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                <p class="text-sm text-gray-700">{{ $currency->description }}</p>
            </div>
            @endif
        </div>
    </div>

    @if($currency->accounts()->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Accounts Using This Currency</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-3">
                This currency is being used by {{ $currency->accounts()->count() }} account(s).
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($currency->accounts()->take(6)->get() as $account)
                <div class="bg-white p-3 rounded border">
                    <div class="text-sm font-medium text-gray-900">{{ $account->account_name }}</div>
                    <div class="text-xs text-gray-500">{{ $account->account_code }}</div>
                </div>
                @endforeach
                @if($currency->accounts()->count() > 6)
                <div class="bg-white p-3 rounded border text-center">
                    <div class="text-sm text-gray-500">+{{ $currency->accounts()->count() - 6 }} more</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="mt-8 flex justify-end space-x-3">
        @if(!$currency->is_base_currency)
            <form action="{{ route('admin.modules.multi-currency.set-base', $currency) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200"
                        onclick="return confirm('Set {{ $currency->name }} as base currency? This will change the exchange rates for all other currencies.')">
                    <i class="fas fa-star mr-2"></i>Set as Base Currency
                </button>
            </form>
        @endif
        
        <a href="{{ route('admin.modules.multi-currency.edit', $currency) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-edit mr-2"></i>Edit Currency
        </a>
    </div>
</div>
@endsection

