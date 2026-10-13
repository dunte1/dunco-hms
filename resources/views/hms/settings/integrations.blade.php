<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2">
                        <a href="{{ route('hms.settings.index') }}" class="hover:text-indigo-600">Settings</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">Integrations</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Integrations</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Configure M-Pesa, DHA Superhighway, and SHA/EHA credentials in-app
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- M-Pesa -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden lg:col-span-1">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-9 h-9 rounded-lg bg-green-50 dark:bg-green-900/40 flex items-center justify-center mr-3">
                                <i class="fas fa-mobile-alt text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">M-Pesa</h3>
                                <p class="text-xs text-gray-500">Safaricom Daraja STK</p>
                            </div>
                        </div>
                        @if($status['mpesa']['configured'])
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Connected</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Not set</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('hms.settings.integrations.mpesa') }}" class="p-5 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Environment</label>
                            <select name="environment" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="sandbox" {{ ($mpesa['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (test)</option>
                                <option value="production" {{ ($mpesa['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (live)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Consumer Key</label>
                            <input type="text" name="consumer_key" value="{{ old('consumer_key', $mpesa['consumer_key'] ?? '') }}" autocomplete="off"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" placeholder="Leave blank to keep current">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Consumer Secret</label>
                            <input type="password" name="consumer_secret" value="" autocomplete="new-password"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" placeholder="{{ filled($mpesa['consumer_secret'] ?? null) ? '•••••••• (saved)' : 'Enter secret' }}">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Shortcode</label>
                                <input type="text" name="shortcode" value="{{ old('shortcode', $mpesa['shortcode'] ?? '') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Passkey</label>
                                <input type="password" name="passkey" value="" autocomplete="new-password"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" placeholder="{{ filled($mpesa['passkey'] ?? null) ? '••••••••' : 'Optional' }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name', $mpesa['business_name'] ?? 'DUNCOHMS') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div class="flex gap-2 pt-1">
                            <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">Save M-Pesa</button>
                            <button type="button" onclick="testIntegration('mpesa')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg">Test</button>
                        </div>
                    </form>
                </div>

                <!-- DHA -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden lg:col-span-1">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center mr-3">
                                <i class="fas fa-network-wired text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">DHA Superhighway</h3>
                                <p class="text-xs text-gray-500">Client / Facility / Provider Registry</p>
                            </div>
                        </div>
                        @if($status['dha']['configured'])
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Connected</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Not set</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('hms.settings.integrations.dha') }}" class="p-5 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Environment</label>
                            <select name="env" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="uat" {{ ($dha['env'] ?? 'uat') === 'uat' ? 'selected' : '' }}>UAT (test)</option>
                                <option value="production" {{ ($dha['env'] ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Client ID</label>
                            <input type="text" name="client_id" value="{{ old('client_id', $dha['client_id'] ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Client Secret</label>
                            <input type="password" name="client_secret" value="" autocomplete="new-password"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" placeholder="{{ filled($dha['client_secret'] ?? null) ? '•••••••• (saved)' : 'Enter secret' }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Facility ID (FRN)</label>
                            <input type="text" name="facility_id" value="{{ old('facility_id', $dha['facility_id'] ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Base URL override (optional)</label>
                            <input type="url" name="base_url" value="{{ old('base_url', $dha['base_url'] ?? '') }}" placeholder="https://api.dha.go.ke/..."
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div class="flex gap-2 pt-1">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">Save DHA</button>
                            <button type="button" onclick="testIntegration('dha')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg">Test</button>
                        </div>
                    </form>
                </div>

                <!-- SHA / EHA -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden lg:col-span-1">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-900/40 flex items-center justify-center mr-3">
                                <i class="fas fa-file-medical text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">SHA / EHA</h3>
                                <p class="text-xs text-gray-500">Eligibility, claims, pre-auth</p>
                            </div>
                        </div>
                        @if($status['eha-sha']['configured'])
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Connected</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Not set</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('hms.settings.integrations.eha') }}" class="p-5 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Environment</label>
                            <select name="env" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="uat" {{ ($eha['env'] ?? 'uat') === 'uat' ? 'selected' : '' }}>UAT (test)</option>
                                <option value="production" {{ ($eha['env'] ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Client ID</label>
                            <input type="text" name="client_id" value="{{ old('client_id', $eha['client_id'] ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Client Secret</label>
                            <input type="password" name="client_secret" value="" autocomplete="new-password"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" placeholder="{{ filled($eha['client_secret'] ?? null) ? '•••••••• (saved)' : 'Enter secret' }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Facility ID (FRN)</label>
                            <input type="text" name="facility_id" value="{{ old('facility_id', $eha['facility_id'] ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Facility ID type</label>
                            <select name="facility_id_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="fr-code" {{ ($eha['facility_id_type'] ?? 'fr-code') === 'fr-code' ? 'selected' : '' }}>fr-code</option>
                            </select>
                        </div>
                        <div class="flex gap-2 pt-1">
                            <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg">Save SHA</button>
                            <button type="button" onclick="testIntegration('eha-sha')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg">Test</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 text-sm text-blue-800 dark:text-blue-200">
                <p class="font-semibold mb-1">Notes</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>Credentials are stored in the database and override <code>.env</code> at runtime.</li>
                    <li>Leave secret fields blank to keep the currently saved value.</li>
                    <li>M-Pesa requires Safaricom Daraja keys; DHA/SHA require facility registration at developer.dha.go.ke.</li>
                    <li>Other insurers (Britam, clergy/mission schemes, etc.) are managed under <a href="{{ route('hms.insurance.providers.index') }}" class="underline">Insurance → Providers</a>.</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function testIntegration(slug) {
            fetch('{{ url('/hms/settings/integrations') }}/test/' + slug, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json()).then(data => {
                alert(data.message || (data.success ? 'OK' : 'Failed'));
            }).catch(() => alert('Test request failed.'));
        }
    </script>
    @endpush
</x-app-layout>
