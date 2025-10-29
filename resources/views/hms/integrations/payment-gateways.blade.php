@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fa fa-money-bill-wave text-green-600 mr-3"></i>
                Payment Gateways Integration
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure payment gateways for M-Pesa, Stripe, and PayPal</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Payment Gateways Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- M-Pesa -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-2 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fa fa-mobile-alt text-3xl text-green-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">M-Pesa</h3>
                </div>
                <span class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded">Popular</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Kenya's leading mobile money service</p>
            <button onclick="openModal('mpesaModal')" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition cursor-pointer">
                <i class="fa fa-cog mr-2"></i> Configure
            </button>
        </div>

        <!-- Stripe -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-2 border-indigo-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fab fa-stripe text-3xl text-indigo-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Stripe</h3>
                </div>
                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded">Global</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">International payment processing</p>
            <button onclick="openModal('stripeModal')" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition cursor-pointer">
                <i class="fa fa-cog mr-2"></i> Configure
            </button>
        </div>

        <!-- PayPal -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-2 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fab fa-paypal text-3xl text-blue-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">PayPal</h3>
                </div>
                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded">Global</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Widely used online payment system</p>
            <button onclick="openModal('paypalModal')" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition cursor-pointer">
                <i class="fa fa-cog mr-2"></i> Configure
            </button>
        </div>
    </div>

    <!-- Configuration Info -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Payment Gateway Setup
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p>To configure payment gateways, you'll need:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>API credentials from your payment provider</li>
                <li>Webhook URLs for transaction notifications</li>
                <li>Test mode enabled for initial testing</li>
            </ul>
            <p class="mt-3"><strong>Note:</strong> Payment gateway configurations will be securely stored and encrypted.</p>
        </div>
    </div>
</div>

<!-- M-Pesa Configuration Modal -->
<div id="mpesaModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="fa fa-mobile-alt text-green-600 mr-3"></i>
                    M-Pesa Configuration
                </h3>
                <button onclick="closeModal('mpesaModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Consumer Key <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="consumer_key" required
                        placeholder="Enter M-Pesa Consumer Key"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Consumer Secret <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="consumer_secret" required
                        placeholder="Enter M-Pesa Consumer Secret"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Business Short Code
                    </label>
                    <input type="text" name="shortcode" 
                        placeholder="Enter Business Short Code"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Passkey
                    </label>
                    <input type="password" name="passkey"
                        placeholder="Enter M-Pesa Passkey"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_mpesa" value="1"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable M-Pesa payments</span>
                    </label>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="test_mode" value="1" checked
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable test/sandbox mode</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('mpesaModal')" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="fa fa-save mr-2"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stripe Configuration Modal -->
<div id="stripeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="fab fa-stripe text-indigo-600 mr-3"></i>
                    Stripe Configuration
                </h3>
                <button onclick="closeModal('stripeModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Publishable Key <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="publishable_key" required
                        placeholder="Enter Stripe Publishable Key"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Secret Key <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="secret_key" required
                        placeholder="Enter Stripe Secret Key"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Webhook Secret
                    </label>
                    <input type="password" name="webhook_secret"
                        placeholder="Enter Webhook Signing Secret"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_stripe" value="1"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable Stripe payments</span>
                    </label>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="test_mode" value="1" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable test mode</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('stripeModal')" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                        <i class="fa fa-save mr-2"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PayPal Configuration Modal -->
<div id="paypalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="fab fa-paypal text-blue-600 mr-3"></i>
                    PayPal Configuration
                </h3>
                <button onclick="closeModal('paypalModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Client ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="client_id" required
                        placeholder="Enter PayPal Client ID"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Client Secret <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="client_secret" required
                        placeholder="Enter PayPal Client Secret"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Mode
                    </label>
                    <select name="mode" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="sandbox">Sandbox (Test)</option>
                        <option value="live">Live (Production)</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_paypal" value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable PayPal payments</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('paypalModal')" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        <i class="fa fa-save mr-2"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('bg-opacity-50')) {
        const modals = ['mpesaModal', 'stripeModal', 'paypalModal'];
        modals.forEach(modalId => {
            closeModal(modalId);
        });
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = ['mpesaModal', 'stripeModal', 'paypalModal'];
        modals.forEach(modalId => {
            closeModal(modalId);
        });
    }
});
</script>
@endsection
