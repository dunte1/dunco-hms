<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-user mr-2 text-blue-500"></i>{{ __('Full Name') }}
            </label>
            <div class="relative">
                <input id="name" 
                       name="name" 
                       type="text" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus 
                       autocomplete="name"
                       placeholder="Enter your full name">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-green-500 opacity-0" id="name-check"></i>
                </div>
            </div>
            @error('name')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-envelope mr-2 text-blue-500"></i>{{ __('Email Address') }}
            </label>
            <div class="relative">
                <input id="email" 
                       name="email" 
                       type="email" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username"
                       placeholder="Enter your email address">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    @if($user->hasVerifiedEmail())
                        <i class="fas fa-check-circle text-green-500" title="Email verified"></i>
                    @else
                        <i class="fas fa-exclamation-triangle text-yellow-500" title="Email not verified"></i>
                    @endif
                </div>
            </div>
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200 font-medium">
                        {{ __('Your email address is unverified.') }}
                            </p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                {{ __('Please verify your email address to ensure you receive important notifications.') }}
                            </p>
                            <button form="send-verification" 
                                    class="mt-2 inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                {{ __('Send Verification Email') }}
                        </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <p class="text-sm text-green-800 dark:text-green-200 font-medium">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Additional Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Phone Field -->
            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-phone mr-2 text-blue-500"></i>{{ __('Phone Number') }}
                </label>
                <input id="phone" 
                       name="phone" 
                       type="tel" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror" 
                       value="{{ old('phone', $user->phone ?? '') }}" 
                       autocomplete="tel"
                       placeholder="Enter your phone number">
                @error('phone')
                    <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Role Field (Read-only) -->
            <div class="space-y-2">
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-user-tag mr-2 text-blue-500"></i>{{ __('Role') }}
                </label>
                <input id="role" 
                       type="text" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 cursor-not-allowed" 
                       value="{{ $user->roles->first()->name ?? 'User' }}" 
                       readonly>
            </div>
        </div>

        <!-- Bio Field -->
        <div class="space-y-2">
            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-user-edit mr-2 text-blue-500"></i>{{ __('Bio') }}
            </label>
            <textarea id="bio" 
                      name="bio" 
                      rows="4" 
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('bio') border-red-500 @enderror" 
                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio ?? '') }}</textarea>
            @error('bio')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-4">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Save Changes') }}
                </button>
                
                <button type="button" 
                        onclick="window.location.reload()"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    {{ __('Cancel') }}
                </button>
            </div>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" 
                    x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-init="setTimeout(() => show = false, 3000)"
                     class="flex items-center px-4 py-2 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-lg border border-green-200 dark:border-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm font-medium">{{ __('Profile updated successfully!') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
