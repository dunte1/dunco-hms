@extends('admin.layouts.app')

@section('title', 'Insurance Policies')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-shield-alt text-blue-600 mr-3"></i>
                        Insurance Policies
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage patient insurance policies and coverage</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.insurance.policies.create') }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center">
                        <i class="fa fa-plus mr-2"></i>
                        New Policy
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-shield-alt text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Policies</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_policies'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-check-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Policies</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['active_policies'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-times-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Expired Policies</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['expired_policies'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-exclamation-triangle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Expiring Soon</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['expiring_soon'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                        ${{ number_format($stats['total_coverage_amount'], 2) }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Coverage Amount</p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                        {{ $stats['primary_policies'] }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Primary Policies</p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                        {{ $stats['today_policies'] }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Today's Policies</p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        {{ $stats['this_month_policies'] }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">This Month</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Search & Filters</h3>
            </div>
            
            <form method="GET" action="{{ route('hms.insurance.policies') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Search
                        </label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}" 
                               placeholder="Search policies..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="insurance_provider_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Insurance Provider
                        </label>
                        <select name="insurance_provider_id" 
                                id="insurance_provider_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Providers</option>
                            @foreach($insuranceProviders as $provider)
                            <option value="{{ $provider->id }}" {{ request('insurance_provider_id') == $provider->id ? 'selected' : '' }}>
                                {{ $provider->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="expiry_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Expiry Filter
                        </label>
                        <select name="expiry_filter" 
                                id="expiry_filter"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Policies</option>
                            <option value="active" {{ request('expiry_filter') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expiring_soon" {{ request('expiry_filter') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="expired" {{ request('expiry_filter') === 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center justify-center">
                            <i class="fa fa-search mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Policies Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Insurance Policies</h3>
            </div>
            
            @if($policies->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Policy Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Patient & Provider
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Coverage Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Dates
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($policies as $policy)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $policy->policy_number }}
                                </div>
                                @if($policy->group_number)
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Group: {{ $policy->group_number }}
                                </div>
                                @endif
                                @if($policy->policy_holder_name)
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    Holder: {{ $policy->policy_holder_name }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $policy->patient->first_name }} {{ $policy->patient->last_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $policy->insuranceProvider->name }}
                                </div>
                                @if($policy->policy_holder_relationship)
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ ucwords($policy->policy_holder_relationship) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($policy->coverage_amount)
                                <div class="text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($policy->coverage_amount, 2) }}
                                </div>
                                @endif
                                @if($policy->coverage_type)
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucwords(str_replace('_', ' ', $policy->coverage_type)) }}
                                </div>
                                @endif
                                @if($policy->copayment_amount)
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    Copay: ${{ number_format($policy->copayment_amount, 2) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    Effective: {{ $policy->effective_date->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Expires: {{ $policy->expiry_date->format('M d, Y') }}
                                </div>
                                @if($policy->expiry_date < now())
                                <div class="text-xs text-red-500 dark:text-red-400">
                                    Expired {{ $policy->expiry_date->diffForHumans() }}
                                </div>
                                @elseif($policy->expiry_date < now()->addDays(30))
                                <div class="text-xs text-yellow-500 dark:text-yellow-400">
                                    Expires {{ $policy->expiry_date->diffForHumans() }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $policy->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        <i class="fa fa-circle mr-1" style="font-size: 6px;"></i>
                                        {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($policy->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Primary
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('hms.insurance.policies.show', $policy) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                       title="View Policy">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('hms.insurance.policies.edit', $policy) }}" 
                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors"
                                       title="Edit Policy">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('hms.insurance.policies.destroy', $policy) }}" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Delete Policy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $policies->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-300 dark:text-gray-600 mb-4">
                    <i class="fa fa-shield-alt text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Insurance Policies</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first insurance policy.</p>
                <a href="{{ route('hms.insurance.policies.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fa fa-plus mr-2"></i>
                    Create Insurance Policy
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
