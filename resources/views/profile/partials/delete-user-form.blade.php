<section class="space-y-6" x-data="{ expanded: false }">
    <button @click="expanded = !expanded" 
            class="w-full flex items-center justify-between p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all duration-300 group">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-rose-500 text-xl mr-3 animate-pulse group-hover:animate-none"></i>
            <h3 class="text-lg font-semibold text-rose-900 dark:text-rose-200">
                {{ __('Danger Zone') }}
            </h3>
        </div>
        <i class="fas fa-chevron-down text-rose-500 transform transition-transform duration-300" :class="{ 'rotate-180': expanded }"></i>
    </button>
    
    <div x-show="expanded" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         style="display: none;"
         class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl p-6"
         x-bind:style="expanded ? 'display: block;' : 'display: none;'">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-rose-500 text-xl animate-pulse"></i>
            </div>
            <div class="ml-3 flex-1">
                <div class="mt-2 text-sm text-rose-800 dark:text-rose-200">
                    <p class="mb-3 leading-relaxed">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.') }}
                    </p>
                    <div class="bg-rose-100 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-700 rounded-lg p-4">
                        <h4 class="font-medium text-rose-900 dark:text-rose-100 mb-2">What will be deleted:</h4>
                        <ul class="text-sm text-rose-800 dark:text-rose-200 space-y-1 leading-relaxed">
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Your profile information and settings
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                All associated data and records
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Access to all system features
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Any uploaded files or documents
                            </li>
                        </ul>
                    </div>
                    <p class="mt-3 font-medium leading-relaxed">
                        {{ __('Before deleting your account, please download any data or information that you wish to retain.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>
                {{ __('Export My Data') }}
            </button>
            
            <button class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-question-circle mr-2"></i>
                {{ __('Get Help') }}
            </button>
        </div>
        
        <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        >
            <i class="fas fa-trash mr-2"></i>
            {{ __('Delete Account') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ __('Delete Account Confirmation') }}
                    </h2>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-red-800 font-medium mb-2">
                    {{ __('This action is irreversible!') }}
                </p>
                <p class="text-sm text-red-700">
                    {{ __('Are you absolutely sure you want to delete your account? This will permanently remove all your data and cannot be undone.') }}
                </p>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
            @csrf
            @method('delete')

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-key mr-2 text-red-500"></i>
                        {{ __('Enter your password to confirm') }}
                    </label>
                    <div class="relative">
                        <input
                    id="password"
                    name="password"
                    type="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors @error('password', 'userDeletion') border-red-500 @enderror"
                            placeholder="{{ __('Enter your current password') }}"
                            required
                        />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" 
                                    class="text-gray-400 hover:text-gray-600 focus:outline-none"
                                    onclick="togglePasswordVisibility('password')">
                                <i class="fas fa-eye" id="toggle-password"></i>
                            </button>
                        </div>
                    </div>
                    @error('password', 'userDeletion')
                        <p class="text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-info-circle text-yellow-500 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">
                                {{ __('Final Warning') }}
                            </p>
                            <p class="text-sm text-yellow-700 mt-1">
                                {{ __('By clicking "Delete Account" below, you acknowledge that you understand this action is permanent and cannot be undone.') }}
                            </p>
                        </div>
                    </div>
            </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <button type="button" 
                            x-on:click="$dispatch('close')"
                            class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                    {{ __('Cancel') }}
                    </button>

                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-trash mr-2"></i>
                        {{ __('Yes, Delete My Account') }}
                    </button>
            </div>
        </form>
        </div>
    </x-modal>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = document.getElementById('toggle-' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }
    </script>
</section>
