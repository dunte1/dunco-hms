@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fa fa-key text-indigo-600 mr-3"></i>
                API Keys Management
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage API tokens for external system access</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('new_token'))
        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg">
            <p class="font-semibold mb-2">⚠️ Important: Copy your API token now!</p>
            <p class="text-sm mb-2">This token will not be shown again for security reasons.</p>
            <div class="bg-white dark:bg-gray-800 p-3 rounded border-2 border-yellow-400">
                <code class="text-sm break-all font-mono">{{ session('new_token') }}</code>
                <button onclick="copyToken(this)" class="ml-2 px-3 py-1 bg-yellow-600 text-white rounded text-sm">
                    <i class="fa fa-copy"></i> Copy
                </button>
            </div>
        </div>
    @endif

    <!-- Create New API Key -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Create New API Key</h3>
        <form action="{{ route('hms.system.api-keys.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Token Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="e.g., Mobile App, Integration Service"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Expires At
                    </label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    @error('expires_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Permissions (Abilities)
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="abilities[]" value="*" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">All Permissions</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="abilities[]" value="patients:read"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Read Patients</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="abilities[]" value="patients:write"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Write Patients</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="abilities[]" value="appointments:read"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Read Appointments</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="abilities[]" value="appointments:write"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Write Appointments</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                <i class="fa fa-plus mr-2"></i> Generate API Key
            </button>
        </form>
    </div>

    <!-- Existing API Keys -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Active API Keys</h3>
        </div>
        
        @if($tokens->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Token</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Used</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Expires</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($tokens as $token)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $token->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Created {{ $token->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">
                                        {{ substr($token->token, 0, 20) }}...
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    @if($token->abilities && in_array('*', $token->abilities))
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">All</span>
                                    @elseif($token->abilities)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                            {{ count($token->abilities) }} permission(s)
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $token->last_used_at ? $token->last_used_at->format('M d, Y H:i') : 'Never' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($token->expires_at)
                                        @if($token->expires_at->isFuture())
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ $token->expires_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-sm text-red-600">Expired</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500">Never</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('hms.system.api-keys.destroy', $token) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to revoke this API key?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400" title="Revoke">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 dark:text-gray-500">
                    <i class="fa fa-key text-6xl mb-4"></i>
                    <p class="text-lg font-medium">No API keys found</p>
                    <p class="text-sm mt-2">Create your first API key above</p>
                </div>
            </div>
        @endif
    </div>

    <!-- API Usage Information -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> API Usage Instructions
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p><strong>Authentication Header:</strong></p>
            <code class="block bg-white dark:bg-gray-800 p-2 rounded mt-1">Authorization: Bearer YOUR_API_TOKEN</code>
            <p class="mt-3"><strong>Base URL:</strong></p>
            <code class="block bg-white dark:bg-gray-800 p-2 rounded mt-1">{{ url('/api') }}</code>
            <p class="mt-3">Example API endpoint: <code>{{ url('/api/patients') }}</code></p>
        </div>
    </div>
</div>

<script>
    function copyToken(button) {
        const tokenElement = button.previousElementSibling;
        const token = tokenElement.textContent.trim();
        
        navigator.clipboard.writeText(token).then(() => {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fa fa-check"></i> Copied!';
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        });
    }
</script>
@endsection

