<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profile Settings') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: {{ auth()->user()->updated_at->format('M d, Y \a\t g:i A') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl mb-8">
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-12">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                        <!-- Avatar Section -->
                        <div class="relative group">
                            <div id="avatar-container" class="w-32 h-32 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center shadow-2xl border-4 border-white dark:border-gray-600 overflow-hidden">
                                @if(auth()->user()->profile_photo_path)
                                    <img id="avatar-image" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div id="avatar-placeholder" class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white text-4xl font-bold">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <!-- Loading Overlay -->
                                <div id="upload-loading" class="hidden absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <i class="fas fa-spinner fa-spin text-white text-2xl"></i>
                                </div>
                            </div>
                            
                            <!-- Upload Button -->
                            <label for="profile-photo" class="absolute -bottom-2 -right-2 bg-white dark:bg-gray-700 rounded-full p-3 shadow-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 group-hover:scale-110">
                                <i id="camera-icon" class="fas fa-camera text-gray-600 dark:text-gray-300 text-lg"></i>
                                <input type="file" id="profile-photo" name="profile_photo" accept="image/jpeg,image/jpg,image/png,image/gif" class="hidden">
                            </label>
                            
                            <!-- Status Indicator -->
                            <div class="absolute top-2 right-2 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-700"></div>
                        </div>

                        <!-- User Info -->
                        <div class="text-center md:text-left text-white flex-1">
                            <h1 class="text-3xl font-bold mb-2">{{ auth()->user()->name }}</h1>
                            <p class="text-blue-100 text-lg mb-1">{{ auth()->user()->email }}</p>
                            <p class="text-blue-200 text-sm mb-4">
                                {{ auth()->user()->roles->first()->name ?? 'User' }} • 
                                Member since {{ auth()->user()->created_at->format('M Y') }}
                            </p>
                            
                            <!-- Email Verification Status -->
                            <div class="flex items-center justify-center md:justify-start space-x-2">
                                @if(auth()->user()->email_verified_at)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4">
                                <div class="text-2xl font-bold">{{ auth()->user()->roles->count() }}</div>
                                <div class="text-sm text-blue-100">Roles</div>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4">
                                <div class="text-2xl font-bold">{{ auth()->user()->permissions->count() }}</div>
                                <div class="text-sm text-blue-100">Permissions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                            Profile Information
                        </h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-shield-alt text-green-600 mr-3"></i>
                            Security Settings
                        </h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Activity & Sessions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-history text-purple-600 mr-3"></i>
                            Activity & Sessions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-sign-in-alt text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Last Login</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y \a\t g:i A') : 'Never' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-desktop text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Current Session</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ request()->ip() }} • {{ request()->userAgent() ? substr(request()->userAgent(), 0, 50) . '...' : 'Unknown Device' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Logout from All Devices
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-bolt text-orange-600 mr-3"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <a href="#" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 rounded-lg transition duration-200 group">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-700 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-download text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Export Data</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Download your account data</p>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-4 bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 rounded-lg transition duration-200 group">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-700 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-bell text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Notifications</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage your preferences</p>
                                </div>
                            </a>

                            <a href="{{ route('hms.system.theme') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900 hover:bg-purple-100 dark:hover:bg-purple-800 rounded-lg transition duration-200 group">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-700 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-palette text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Theme Settings</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Customize appearance</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900 dark:to-pink-900 px-6 py-4 border-b border-red-200 dark:border-red-700">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-3 animate-pulse"></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Delete Account
                                </h4>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                                </div>
                                <div class="mt-4">
                                    @include('profile.partials.delete-user-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Store user data for form submission
        const userData = {
            name: @json(auth()->user()->name),
            email: @json(auth()->user()->email),
            phone: @json(auth()->user()->phone ?? ''),
            bio: @json(auth()->user()->bio ?? ''),
        };

        // Store original avatar HTML for rollback
        let originalAvatarHTML = '';
        
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('profile-photo');
            const avatarContainer = document.getElementById('avatar-container');
            originalAvatarHTML = avatarContainer.innerHTML;
            
            if (input) {
                input.addEventListener('change', handleProfilePhotoUpload);
            }

            // Page load animation
            animatePageLoad();
        });

        function handleProfilePhotoUpload(event) {
            const input = event.target;
            const file = input.files[0];
            
            if (!file) {
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showNotification('Please select a valid image file (JPEG, PNG, or GIF)', 'error');
                input.value = '';
                return;
            }
            
            // Validate file size (max 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                showNotification('Image size should be less than 2MB', 'error');
                input.value = '';
                return;
            }

            // Show loading state
            showLoadingState(true);

            // Get current form values for name, email, phone, bio
            const nameInput = document.querySelector('input[name="name"]');
            const emailInput = document.querySelector('input[name="email"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const bioInput = document.querySelector('textarea[name="bio"]');
            
            // Create form data - Include required fields + photo
            const formData = new FormData();
            formData.append('profile_photo', file);
            formData.append('name', nameInput ? nameInput.value : userData.name);
            formData.append('email', emailInput ? emailInput.value : userData.email);
            
            // Optional fields - use actual form values if available
            if (phoneInput && phoneInput.value) {
                formData.append('phone', phoneInput.value);
            } else if (userData.phone) {
                formData.append('phone', userData.phone);
            }
            
            if (bioInput && bioInput.value) {
                formData.append('bio', bioInput.value);
            } else if (userData.bio) {
                formData.append('bio', userData.bio);
            }
            
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');

            // Submit via fetch
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                // Parse JSON response
                const data = await response.json().catch(() => {
                    return { success: false, message: 'Invalid response from server' };
                });
                
                // Check if response is ok
                if (!response.ok) {
                    console.error('Server error:', data);
                    return Promise.reject(data);
                }
                
                return data;
            })
            .then(data => {
                console.log('Success response:', data);
                showLoadingState(false);
                
                if (data.success || data.status === 'success' || data.message) {
                    showNotification(data.message || 'Profile photo updated successfully!', 'success');
                    
                    // Update avatar with new photo
                    const photoUrl = data.photo_url || data.profile_photo_url || data.profile_photo_path;
                    if (photoUrl) {
                        updateAvatarImage(photoUrl);
                    }
                    
                    // Clear input
                    input.value = '';
                    
                    // Reload page after 1 second to show updated photo
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to upload photo');
                }
            })
            .catch(error => {
                console.error('Upload Error:', error);
                showLoadingState(false);
                
                // Handle Laravel validation errors
                let errorMessage = 'Failed to upload photo. Please try again.';
                
                if (error.errors) {
                    // Laravel validation errors
                    const errorMessages = [];
                    for (const field in error.errors) {
                        const fieldErrors = error.errors[field];
                        if (Array.isArray(fieldErrors)) {
                            errorMessages.push(...fieldErrors);
                        } else {
                            errorMessages.push(fieldErrors);
                        }
                    }
                    errorMessage = errorMessages.join(', ');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                showNotification(errorMessage, 'error');
                
                // Rollback avatar
                rollbackAvatar();
                
                // Clear input
                input.value = '';
            });
        }

        function showLoadingState(isLoading) {
            const loadingOverlay = document.getElementById('upload-loading');
            const input = document.getElementById('profile-photo');
            const cameraIcon = document.getElementById('camera-icon');
            
            if (isLoading) {
                loadingOverlay?.classList.remove('hidden');
                if (input) input.disabled = true;
                if (cameraIcon) {
                    cameraIcon.dataset.originalClass = cameraIcon.className;
                    cameraIcon.className = 'fas fa-spinner fa-spin text-gray-600 dark:text-gray-300 text-lg';
                }
            } else {
                loadingOverlay?.classList.add('hidden');
                if (input) input.disabled = false;
                if (cameraIcon && cameraIcon.dataset.originalClass) {
                    cameraIcon.className = cameraIcon.dataset.originalClass;
                }
            }
        }

        function updateAvatarImage(photoUrl) {
            if (!photoUrl) return;
            
            // Handle different URL formats
            let imageUrl = photoUrl;
            if (!photoUrl.startsWith('http') && !photoUrl.startsWith('/')) {
                imageUrl = '/storage/' + photoUrl;
            }
            
            const avatarContainer = document.getElementById('avatar-container');
            const avatarImage = document.getElementById('avatar-image');
            const avatarPlaceholder = document.getElementById('avatar-placeholder');
            
            // Add cache buster
            imageUrl = imageUrl + '?t=' + Date.now();
            
            if (avatarImage) {
                // Update existing image
                avatarImage.src = imageUrl;
            } else if (avatarPlaceholder) {
                // Replace placeholder with image
                avatarPlaceholder.outerHTML = `<img id="avatar-image" src="${imageUrl}" alt="Profile" class="w-full h-full object-cover">`;
            } else if (avatarContainer) {
                // Create new image
                const loadingOverlay = avatarContainer.querySelector('#upload-loading');
                avatarContainer.innerHTML = `<img id="avatar-image" src="${imageUrl}" alt="Profile" class="w-full h-full object-cover">`;
                if (loadingOverlay) {
                    avatarContainer.appendChild(loadingOverlay);
                }
            }
            
            // Update original HTML for future rollbacks
            setTimeout(() => {
                originalAvatarHTML = avatarContainer.innerHTML;
            }, 100);
        }

        function rollbackAvatar() {
            const avatarContainer = document.getElementById('avatar-container');
            if (avatarContainer && originalAvatarHTML) {
                avatarContainer.innerHTML = originalAvatarHTML;
            }
        }

        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(n => n.remove());
            
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
            
            notification.className = `notification-toast fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center max-w-md transition-all duration-300 transform translate-x-0`;
            notification.innerHTML = `
                <i class="fas ${icon} mr-2"></i>
                <span>${escapeHtml(message)}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(notification);
            
            // Slide in animation
            setTimeout(() => {
                notification.style.opacity = '1';
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function animatePageLoad() {
            const cards = document.querySelectorAll('.bg-white, .bg-gray-800');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
    </script>
    @endpush

    <style>
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        .notification-toast {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        #upload-loading {
            transition: opacity 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .bg-gradient-to-r.from-blue-600 {
                padding: 1.5rem !important;
            }
            
            .w-32.h-32 {
                width: 6rem;
                height: 6rem;
            }
            
            .text-3xl {
                font-size: 1.5rem;
            }
            
            .grid.grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.75rem;
            }
        }

        @media (max-width: 640px) {
            .max-w-7xl {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .flex.flex-col.md\:flex-row {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .text-center.md\:text-left {
                text-align: center !important;
            }
            
            .justify-center.md\:justify-start {
                justify-content: center !important;
            }
        }
    </style>
</x-app-layout>