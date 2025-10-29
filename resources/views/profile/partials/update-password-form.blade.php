<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Current Password Field -->
        <div class="space-y-2">
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">
                <i class="fas fa-key mr-2 text-green-500"></i>{{ __('Current Password') }}
            </label>
            <div class="relative">
                <input id="update_password_current_password" 
                       name="current_password" 
                       type="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('current_password', 'updatePassword') border-red-500 @enderror" 
                       autocomplete="current-password"
                       placeholder="Enter your current password"
                       required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                            onclick="togglePasswordVisibility('update_password_current_password')">
                        <i class="fas fa-eye" id="toggle-current-password"></i>
                    </button>
                </div>
            </div>
            @error('current_password', 'updatePassword')
                <p class="text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- New Password Field -->
        <div class="space-y-2">
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">
                <i class="fas fa-lock mr-2 text-green-500"></i>{{ __('New Password') }}
            </label>
            <div class="relative">
                <input id="update_password_password" 
                       name="password" 
                       type="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password', 'updatePassword') border-red-500 @enderror" 
                       autocomplete="new-password"
                       placeholder="Enter your new password"
                       required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                            onclick="togglePasswordVisibility('update_password_password')">
                        <i class="fas fa-eye" id="toggle-new-password"></i>
                    </button>
                </div>
            </div>
            @error('password', 'updatePassword')
                <p class="text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
            
            <!-- Password Strength Indicator -->
            <div class="mt-2">
                <div class="flex items-center space-x-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div id="password-strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <span id="password-strength-text" class="text-xs text-gray-500">Enter password</span>
                </div>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="space-y-2">
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">
                <i class="fas fa-lock mr-2 text-green-500"></i>{{ __('Confirm New Password') }}
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                       autocomplete="new-password"
                       placeholder="Confirm your new password"
                       required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                            onclick="togglePasswordVisibility('update_password_password_confirmation')">
                        <i class="fas fa-eye" id="toggle-confirm-password"></i>
                    </button>
                </div>
            </div>
            @error('password_confirmation', 'updatePassword')
                <p class="text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password Requirements -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-blue-900 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Password Requirements
            </h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2" id="req-length"></i>
                    At least 8 characters long
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2" id="req-uppercase"></i>
                    Contains uppercase letter
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2" id="req-lowercase"></i>
                    Contains lowercase letter
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2" id="req-number"></i>
                    Contains number
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2" id="req-special"></i>
                    Contains special character
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-4">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Update Password') }}
                </button>
                
                <button type="button" 
                        class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    {{ __('Cancel') }}
                </button>
            </div>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" 
                    x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-init="setTimeout(() => show = false, 3000)"
                     class="flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm font-medium">{{ __('Password updated successfully!') }}</span>
                </div>
            @endif
        </div>
    </form>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = document.getElementById('toggle-' + fieldId.replace('update_password_', ''));
            
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

        // Password strength checker
        document.getElementById('update_password_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            let strength = 0;
            let requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const icon = document.getElementById('req-' + req);
                if (requirements[req]) {
                    icon.classList.remove('fa-times', 'text-red-500');
                    icon.classList.add('fa-check', 'text-green-500');
                    strength++;
                } else {
                    icon.classList.remove('fa-check', 'text-green-500');
                    icon.classList.add('fa-times', 'text-red-500');
                }
            });
            
            // Update strength bar and text
            const percentage = (strength / 5) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (strength === 0) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-gray-300';
                strengthText.textContent = 'Enter password';
                strengthText.className = 'text-xs text-gray-500';
            } else if (strength < 3) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs text-red-500';
            } else if (strength < 5) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
                strengthText.textContent = 'Medium';
                strengthText.className = 'text-xs text-yellow-500';
            } else {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs text-green-500';
            }
        });
    </script>
</section>
