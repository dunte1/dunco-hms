@extends('layouts.app')

@section('title', 'Insurance Dashboard')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-shield-halved text-indigo-600 mr-3"></i> Insurance Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Overview of insurance providers, policies, and claims</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('hms.insurance.companies') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                <i class="fa-solid fa-building mr-2"></i> Companies
            </a>
            <a href="{{ route('hms.insurance.policies') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                <i class="fa-solid fa-file-shield mr-2"></i> Policies
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Providers</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_providers'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-building text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Active Policies</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active_policies'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-file-shield text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Pending Claims</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['pending_claims'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Claims</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_claims'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Claims -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-file-invoice-dollar mr-2 text-indigo-600"></i> Recent Claims
                </h2>
                <a href="{{ route('hms.insurance.companies') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            <div class="p-6 space-y-3">
                @forelse($recentClaims ?? [] as $claim)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $claim->patient->first_name ?? 'N/A' }} {{ $claim->patient->last_name ?? '' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $claim->insuranceProvider->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($claim->claimed_amount ?? 0, 2) }}</p>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $claim->status === 'approved' ? 'bg-green-100 text-green-800' : ($claim->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($claim->status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fa-solid fa-file-invoice-dollar text-3xl mb-2"></i>
                        <p class="text-sm">No recent claims</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Policies -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-file-shield mr-2 text-blue-600"></i> Recent Policies
                </h2>
                <a href="{{ route('hms.insurance.policies') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="p-6 space-y-3">
                @forelse($recentPolicies ?? [] as $policy)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $policy->patient->first_name ?? 'N/A' }} {{ $policy->patient->last_name ?? '' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $policy->insuranceProvider->name ?? 'N/A' }} · {{ $policy->policy_number ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($policy->coverage_amount ?? 0, 2) }}</p>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ ($policy->is_active ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ($policy->is_active ?? false) ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fa-solid fa-file-shield text-3xl mb-2"></i>
                        <p class="text-sm">No recent policies</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
