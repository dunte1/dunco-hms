<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Manage your account information, security, and preferences
                    </p>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Last updated: {{ auth()->user()->updated_at->format('M d, Y \a\t g:i A') }}
                </div>
            </div>

            @if(session('status') === 'profile-updated')
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">Profile updated successfully!</p>
                    </div>
                </div>
            @endif

            <!-- Profile Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-6" style="background: linear-gradient(135deg, {{ $themeSettings['primary_color'] ?? '#2563EB' }} 0%, {{ $themeSettings['secondary_color'] ?? '#1D4ED8' }} 100%);">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                        <!-- Avatar -->
                        <div class="relative shrink-0" style="width: 112px; height: 112px;">
                            <div id="avatar-container"
                                 style="width: 112px; height: 112px; border-radius: 9999px; overflow: hidden; background: rgba(255,255,255,0.18); border: 4px solid rgba(255,255,255,0.55); box-shadow: 0 8px 20px rgba(0,0,0,0.18); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                @php
                                    $photoUrl = \App\Support\ProfilePhoto::url(auth()->user()->profile_photo_path ?? null);
                                @endphp
                                @if($photoUrl)
                                    <img id="avatar-image"
                                         src="{{ $photoUrl }}"
                                         alt="{{ auth()->user()->name }}"
                                         style="width: 100%; height: 100%; object-fit: cover; display: block;"
                                         onerror="this.style.display='none';var p=document.getElementById('avatar-placeholder');if(p){p.style.display='flex';}">
                                    <div id="avatar-placeholder" style="display:none; width:100%; height:100%; align-items:center; justify-content:center; background: linear-gradient(135deg,#3b82f6,#8b5cf6);">
                                        <span style="color:#fff; font-size:1.75rem; font-weight:700;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                    </div>
                                @else
                                    <div id="avatar-placeholder" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg,#3b82f6,#8b5cf6);">
                                        <span style="color:#fff; font-size:1.75rem; font-weight:700;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div id="upload-loading" style="display:none; position:absolute; inset:0; background:rgba(0,0,0,0.45); align-items:center; justify-content:center; border-radius:9999px;">
                                    <i class="fas fa-spinner fa-spin" style="color:#fff; font-size:1.5rem;"></i>
                                </div>
                            </div>

                            <label for="profile-photo"
                                   style="position:absolute; bottom:-4px; right:-4px; background:#fff; border-radius:9999px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 10px rgba(0,0,0,0.18);"
                                   title="Change photo">
                                <i id="camera-icon" class="fas fa-camera" style="color:#4b5563; font-size:0.875rem;"></i>
                                <input type="file" id="profile-photo" name="profile_photo" accept="image/jpeg,image/jpg,image/png,image/gif" style="display:none;">
                            </label>
                        </div>

                        <!-- User Info -->
                        <div class="text-center sm:text-left text-white flex-1 min-w-0">
                            <h2 class="text-xl font-bold truncate" style="margin:0;">{{ auth()->user()->name }}</h2>
                            <p class="truncate" style="margin:4px 0 0; color:rgba(255,255,255,0.85); font-size:0.95rem;">{{ auth()->user()->email }}</p>
                            <div class="mt-3 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(255,255,255,0.2); color:#fff;">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                                </span>
                                @if(auth()->user()->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#22c55e; color:#fff;">
                                        <i class="fas fa-check-circle mr-1"></i> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#eab308; color:#fff;">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Unverified
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(255,255,255,0.2); color:#fff;">
                                    Member since {{ auth()->user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-3 w-full sm:w-auto" style="flex-shrink:0;">
                            <div style="background:rgba(255,255,255,0.16); border-radius:10px; padding:10px 14px; text-align:center; min-width:84px;">
                                <div style="font-size:1.35rem; font-weight:700; color:#fff;">{{ auth()->user()->roles->count() }}</div>
                                <div style="font-size:0.72rem; color:rgba(255,255,255,0.85); margin-top:2px;">Roles</div>
                            </div>
                            <div style="background:rgba(255,255,255,0.16); border-radius:10px; padding:10px 14px; text-align:center; min-width:84px;">
                                <div style="font-size:1.35rem; font-weight:700; color:#fff;">{{ auth()->user()->permissions->count() }}</div>
                                <div style="font-size:0.72rem; color:rgba(255,255,255,0.85); margin-top:2px;">Permissions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center mr-3">
                            <i class="fas fa-user-edit text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Update your personal details</p>
                        </div>
                    </div>
                    <div class="p-5">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <div class="w-9 h-9 rounded-lg bg-green-50 dark:bg-green-900/40 flex items-center justify-center mr-3">
                            <i class="fas fa-shield-alt text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Security</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Change password and session settings</p>
                        </div>
                    </div>
                    <div class="p-5">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-900/40 flex items-center justify-center mr-3">
                            <i class="fas fa-history text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Activity</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Session and login information</p>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Last Login</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y \a\t g:i A') : 'Never' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                    <i class="fas fa-desktop text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Current Session</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ request()->ip() }} • {{ request()->userAgent() ? \Illuminate\Support\Str::limit(request()->userAgent(), 48) : 'Unknown device' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition flex items-center justify-center">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout from All Devices
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 dark:bg-orange-900/40 flex items-center justify-center mr-3">
                            <i class="fas fa-bolt text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Common account shortcuts</p>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <a href="{{ route('hms.system.theme') }}" class="flex items-center p-3.5 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition group">
                            <div class="w-9 h-9 bg-purple-100 dark:bg-purple-800 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform">
                                <i class="fas fa-palette text-purple-600 dark:text-purple-300"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Theme & Branding</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Customize colors and logo</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-400 text-xs"></i>
                        </a>

                        <a href="{{ route('hms.settings.index') }}" class="flex items-center p-3.5 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition group">
                            <div class="w-9 h-9 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform">
                                <i class="fas fa-cog text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">System Settings</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Hospital configuration</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-400 text-xs"></i>
                        </a>

                        <a href="{{ route('dashboard') }}" class="flex items-center p-3.5 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition group">
                            <div class="w-9 h-9 bg-green-100 dark:bg-green-800 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform">
                                <i class="fas fa-tachometer-alt text-green-600 dark:text-green-300"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Dashboard</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Return to main dashboard</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-400 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-red-200 dark:border-red-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 flex items-center">
                    <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-800/60 flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-red-900 dark:text-red-100">Danger Zone</h3>
                        <p class="text-xs text-red-700 dark:text-red-300">Irreversible account actions</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Delete Account</h4>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                        <div class="mt-4">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const userData = {
            name: @json(auth()->user()->name),
            email: @json(auth()->user()->email),
            phone: @json(auth()->user()->phone ?? ''),
            bio: @json(auth()->user()->bio ?? ''),
        };

        let originalAvatarHTML = '';

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('profile-photo');
            const avatarContainer = document.getElementById('avatar-container');
            if (avatarContainer) {
                originalAvatarHTML = avatarContainer.innerHTML;
            }
            if (input) {
                input.addEventListener('change', handleProfilePhotoUpload);
            }
        });

        function handleProfilePhotoUpload(event) {
            const input = event.target;
            const file = input.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showNotification('Please select a valid image file (JPEG, PNG, or GIF)', 'error');
                input.value = '';
                return;
            }

            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                showNotification('Image size should be less than 2MB', 'error');
                input.value = '';
                return;
            }

            showLoadingState(true);

            const nameInput = document.querySelector('input[name="name"]');
            const emailInput = document.querySelector('input[name="email"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const bioInput = document.querySelector('textarea[name="bio"]');

            const formData = new FormData();
            formData.append('profile_photo', file);
            formData.append('name', nameInput ? nameInput.value : userData.name);
            formData.append('email', emailInput ? emailInput.value : userData.email);
            if (phoneInput && phoneInput.value) formData.append('phone', phoneInput.value);
            else if (userData.phone) formData.append('phone', userData.phone);
            if (bioInput && bioInput.value) formData.append('bio', bioInput.value);
            else if (userData.bio) formData.append('bio', userData.bio);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');

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
                const data = await response.json().catch(() => ({ success: false, message: 'Invalid response from server' }));
                if (!response.ok) return Promise.reject(data);
                return data;
            })
            .then(data => {
                showLoadingState(false);
                if (data.success || data.status === 'success' || data.message) {
                    showNotification(data.message || 'Profile photo updated successfully!', 'success');
                    const photoUrl = data.photo_url || data.profile_photo_url || data.profile_photo_path;
                    if (photoUrl) updateAvatarImage(photoUrl);
                    input.value = '';
                    // Keep page open with new avatar; full reload after short delay if needed
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    throw new Error(data.message || 'Failed to upload photo');
                }
            })
            .catch(error => {
                showLoadingState(false);
                let errorMessage = 'Failed to upload photo. Please try again.';
                if (error.errors) {
                    const msgs = [];
                    for (const field in error.errors) {
                        const fieldErrors = error.errors[field];
                        if (Array.isArray(fieldErrors)) msgs.push(...fieldErrors);
                        else msgs.push(fieldErrors);
                    }
                    errorMessage = msgs.join(', ');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                showNotification(errorMessage, 'error');
                rollbackAvatar();
                input.value = '';
            });
        }

        function showLoadingState(isLoading) {
            const loadingOverlay = document.getElementById('upload-loading');
            const input = document.getElementById('profile-photo');
            const cameraIcon = document.getElementById('camera-icon');
            if (isLoading) {
                if (loadingOverlay) loadingOverlay.style.display = 'flex';
                if (input) input.disabled = true;
                if (cameraIcon) {
                    cameraIcon.dataset.originalClass = cameraIcon.className;
                    cameraIcon.className = 'fas fa-spinner fa-spin';
                }
            } else {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                if (input) input.disabled = false;
                if (cameraIcon && cameraIcon.dataset.originalClass) cameraIcon.className = cameraIcon.dataset.originalClass;
            }
        }

        function updateAvatarImage(photoUrl) {
            if (!photoUrl) return;
            let imageUrl = photoUrl;
            if (!photoUrl.startsWith('http') && !photoUrl.startsWith('/')) {
                imageUrl = '/storage/' + photoUrl;
            }
            imageUrl = imageUrl + '?t=' + Date.now();

            const avatarContainer = document.getElementById('avatar-container');
            const avatarImage = document.getElementById('avatar-image');
            const avatarPlaceholder = document.getElementById('avatar-placeholder');

            if (avatarImage) {
                avatarImage.src = imageUrl;
                avatarImage.style.display = 'block';
                avatarImage.style.width = '100%';
                avatarImage.style.height = '100%';
                avatarImage.style.objectFit = 'cover';
                if (avatarPlaceholder) avatarPlaceholder.style.display = 'none';
            } else if (avatarPlaceholder) {
                const img = document.createElement('img');
                img.id = 'avatar-image';
                img.src = imageUrl;
                img.alt = 'Profile';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.display = 'block';
                avatarPlaceholder.replaceWith(img);
            } else if (avatarContainer) {
                const loadingOverlay = avatarContainer.querySelector('#upload-loading');
                avatarContainer.innerHTML = '';
                const img = document.createElement('img');
                img.id = 'avatar-image';
                img.src = imageUrl;
                img.alt = 'Profile';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.display = 'block';
                avatarContainer.appendChild(img);
                if (loadingOverlay) avatarContainer.appendChild(loadingOverlay);
            }
            setTimeout(() => {
                if (avatarContainer) originalAvatarHTML = avatarContainer.innerHTML;
            }, 100);
        }

        function rollbackAvatar() {
            const avatarContainer = document.getElementById('avatar-container');
            if (avatarContainer && originalAvatarHTML) avatarContainer.innerHTML = originalAvatarHTML;
        }

        function showNotification(message, type = 'info') {
            document.querySelectorAll('.notification-toast').forEach(n => n.remove());
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
            notification.className = `notification-toast fixed top-4 right-4 ${bgColor} text-white px-5 py-3 rounded-lg shadow-lg z-50 flex items-center max-w-md`;
            notification.innerHTML = `<i class="fas ${icon} mr-2"></i><span>${escapeHtml(message)}</span><button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200"><i class="fas fa-times"></i></button>`;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
    @endpush
</x-app-layout>
