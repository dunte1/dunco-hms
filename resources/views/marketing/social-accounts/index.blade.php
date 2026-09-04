@extends('layouts.app')

@section('title', 'Social Accounts')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-share-nodes text-blue-600 mr-3"></i> Connected Social Accounts
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your social media integrations</p>
        </div>
        <a href="{{ route('marketing.social-accounts.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Connect Account
        </a>
    </div>

    <!-- Accounts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($accounts as $account)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            @php
                                $platformIcons = [
                                    'facebook' => ['fa-brands fa-facebook', 'bg-blue-600'],
                                    'instagram' => ['fa-brands fa-instagram', 'bg-pink-500'],
                                    'twitter' => ['fa-brands fa-twitter', 'bg-sky-500'],
                                    'linkedin' => ['fa-brands fa-linkedin', 'bg-blue-700'],
                                    'tiktok' => ['fa-brands fa-tiktok', 'bg-gray-900'],
                                ];
                                $icon = $platformIcons[$account->platform] ?? ['fa-solid fa-globe', 'bg-gray-500'];
                            @endphp
                            <div class="p-3 rounded-xl {{ $icon[1] }} text-white">
                                <i class="{{ $icon[0] }} text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($account->platform) }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $account->account_name }}</p>
                            </div>
                        </div>
                        @if($account->is_default)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Default</span>
                        @endif
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Status</span>
                            @if($account->status === 'active')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @elseif($account->status === 'expired')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($account->status) }}</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Connected</span>
                            <span class="text-gray-900 dark:text-white">{{ $account->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('marketing.social-accounts.edit', $account) }}" class="flex-1 px-3 py-2 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-center rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                            <i class="fa-solid fa-edit mr-1"></i> Edit
                        </a>
                        <button onclick="if(confirm('Disconnect this account?')) { document.getElementById('delete-account-{{ $account->id }}').submit(); }" class="px-3 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                            <i class="fa-solid fa-link-slash"></i>
                        </button>
                        <form id="delete-account-{{ $account->id }}" action="{{ route('marketing.social-accounts.destroy', $account) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-share-nodes text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No social accounts connected</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Connect your first social media account to get started</p>
            </div>
        @endforelse
    </div>

    @if($accounts->hasPages())
        <div class="mt-6">
            {{ $accounts->links() }}
        </div>
    @endif
</div>
@endsection
