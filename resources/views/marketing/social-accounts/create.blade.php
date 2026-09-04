@extends('layouts.app')

@section('title', 'Connect Social Account')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
            <i class="fa-solid fa-link text-blue-600 mr-3"></i> Connect Social Account
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Link a new social media platform</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl">
        <!-- Platform Selection -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Select Platform</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" x-data="{ selected: '' }">
                @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'twitter' => 'Twitter/X', 'linkedin' => 'LinkedIn', 'tiktok' => 'TikTok'] as $platform => $label)
                    <button type="button" @click="selected = '{{ $platform }}'; $refs.platform_input.value = '{{ $platform }}'"
                        :class="selected === '{{ $platform }}' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'bg-gray-50 dark:bg-gray-700 hover:bg-gray-100'"
                        class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 transition text-center">
                        @php
                            $icons = [
                                'facebook' => 'fa-brands fa-facebook text-blue-600',
                                'instagram' => 'fa-brands fa-instagram text-pink-500',
                                'twitter' => 'fa-brands fa-twitter text-sky-500',
                                'linkedin' => 'fa-brands fa-linkedin text-blue-700',
                                'tiktok' => 'fa-brands fa-tiktok text-gray-900 dark:text-white',
                            ];
                        @endphp
                        <i class="{{ $icons[$platform] }} text-3xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</p>
                    </button>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('marketing.social-accounts.store') }}">
            @csrf
            <input type="hidden" name="platform" x-ref="platform_input" value="{{ old('platform') }}">

            <!-- Account Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Details</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Name *</label>
                        <input type="text" name="account_name" value="{{ old('account_name') }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g., Dunco Hospital Official">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Access Token (optional)</label>
                        <input type="text" name="access_token" value="{{ old('access_token') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                            placeholder="API access token if available">
                        <p class="text-xs text-gray-500 mt-1">Leave blank if using OAuth connection</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Set as default account for this platform</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa-solid fa-link mr-2"></i> Connect Account
                </button>
                <a href="{{ route('marketing.social-accounts.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
