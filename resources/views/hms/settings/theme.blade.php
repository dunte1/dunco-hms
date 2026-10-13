<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="min-w-0">
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2 flex-wrap">
                        <a href="{{ route('hms.settings.index') }}" class="hover:text-pink-600">Settings</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">Theme & Branding</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Theme & Branding</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Customize hospital colors, logo, and favicon
                    </p>
                </div>
                <a href="{{ route('hms.settings.index') }}"
                   class="shrink-0 inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Settings
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('status'))
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('hms.system.theme.update') }}" enctype="multipart/form-data" id="themeForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Color Scheme -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <div class="w-9 h-9 rounded-lg bg-pink-50 dark:bg-pink-900/40 flex items-center justify-center mr-3">
                                <i class="fas fa-palette text-pink-600 dark:text-pink-400"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Color Scheme</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Brand colors used across the app</p>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Primary Color <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <input type="color" name="primary_color" id="primary_color"
                                           value="{{ old('primary_color', $settings['primary_color']) }}"
                                           class="w-14 h-12 shrink-0 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer bg-white dark:bg-gray-700">
                                    <input type="text" value="{{ old('primary_color', $settings['primary_color']) }}"
                                           class="min-w-0 flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 font-mono text-sm"
                                           readonly id="primary_color_text">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Main brand color — headers, buttons, accents</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Secondary Color <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <input type="color" name="secondary_color" id="secondary_color"
                                           value="{{ old('secondary_color', $settings['secondary_color']) }}"
                                           class="w-14 h-12 shrink-0 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer bg-white dark:bg-gray-700">
                                    <input type="text" value="{{ old('secondary_color', $settings['secondary_color']) }}"
                                           class="min-w-0 flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 font-mono text-sm"
                                           readonly id="secondary_color_text">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Secondary brand color for gradients and hover states</p>
                            </div>

                                <div class="rounded-xl p-4 border border-gray-200 dark:border-gray-600 overflow-hidden"
                                     id="live_preview"
                                     style="background: linear-gradient(135deg, {{ old('primary_color', $settings['primary_color']) }} 0%, {{ old('secondary_color', $settings['secondary_color']) }} 100%); min-height: 120px;">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 shrink-0 rounded-lg bg-white/20 flex items-center justify-center">
                                            <i class="fas fa-hospital text-white"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-white font-bold truncate">{{ $themeSettings['hospital_name'] ?? config('app.name') }}</p>
                                            <p class="text-white/80 text-xs truncate">Live brand preview</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white" style="background: rgba(255,255,255,0.2);">Primary</span>
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white" style="background: rgba(255,255,255,0.35);">Secondary</span>
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-gray-900">Button</span>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- Branding -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center mr-3">
                                <i class="fas fa-image text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Branding Assets</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Hospital logo and browser favicon</p>
                            </div>
                        </div>
                        <div class="p-5 space-y-5">
                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hospital Logo</label>
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition px-3 text-center" for="hospital_logo">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Click to upload logo</span>
                                    <span class="text-xs text-gray-400 mt-1">Recommended: 200×60 PNG</span>
                                    <input type="file" name="hospital_logo" id="hospital_logo" class="hidden" accept="image/*,.png,.jpg,.jpeg,.gif,.webp">
                                </label>
                                <input type="hidden" name="hospital_logo_cropped" id="hospital_logo_cropped">
                                <div id="logoPreviewWrap" class="mt-3 hidden">
                                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                    <div class="flex items-center gap-3">
                                        <img id="logoPreviewImg" alt="Logo Preview" class="max-h-16 max-w-[70%] object-contain rounded border border-gray-200 dark:border-gray-600 p-1 bg-white">
                                        <button type="button" onclick="removeLogo()" class="shrink-0 text-xs text-red-600 hover:text-red-700">
                                            <i class="fa fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                                @if($settings['hospital_logo'])
                                    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <p class="text-xs text-gray-500 mb-1">Current logo:</p>
                                        <img src="{{ \App\Support\Logo::url($settings['hospital_logo'] ?? null) }}" alt="Current Logo" class="max-h-14 max-w-full object-contain rounded" onerror="this.style.display='none'">
                                    </div>
                                @endif
                            </div>

                            <hr class="border-gray-200 dark:border-gray-700">

                            <!-- Favicon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                                <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition px-3 text-center" for="favicon">
                                    <i class="fas fa-icons text-2xl text-gray-400 mb-2"></i>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Click to upload favicon</span>
                                    <span class="text-xs text-gray-400 mt-1">Recommended: 32×32 PNG/ICO</span>
                                    <input type="file" name="favicon" id="favicon" class="hidden" accept="image/*,.ico,.png">
                                </label>
                                <div id="faviconPreviewWrap" class="mt-3 hidden">
                                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                    <div class="flex items-center gap-3">
                                        <img id="faviconPreviewImg" alt="Favicon Preview" class="w-8 h-8 shrink-0 rounded border border-gray-200 dark:border-gray-600">
                                        <button type="button" onclick="removeFavicon()" class="text-xs text-red-600 hover:text-red-700">
                                            <i class="fa fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                                @if($settings['favicon'])
                                    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex items-center gap-3">
                                        <img src="{{ \App\Support\Favicon::url($settings['favicon'] ?? null) }}" alt="Current Favicon" class="w-8 h-8 shrink-0 rounded" onerror="this.style.display='none'">
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500">Current favicon</p>
                                            <p class="text-xs text-gray-400">Applied on next page load</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Preferences -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-900/40 flex items-center justify-center mr-3">
                            <i class="fas fa-moon text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Display Preferences</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dark mode and appearance defaults</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="dark_mode" id="darkMode" value="1"
                                   {{ $settings['dark_mode'] ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Enable Dark Mode by default</p>
                                <p class="text-xs text-gray-500">Users can still toggle from the navbar</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mt-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-lightbulb text-amber-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">Branding tips</p>
                            <ul class="mt-1 text-xs text-amber-800 dark:text-amber-300 space-y-0.5 list-disc list-inside">
                                <li>Use high-contrast colors for accessibility</li>
                                <li>Keep the logo clear at small sizes (sidebar + navbar)</li>
                                <li>Favicon appears in browser tabs after save</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end mt-6">
                    <a href="{{ route('hms.settings.index') }}"
                       class="w-full sm:w-auto px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 text-white font-semibold rounded-lg text-center"
                            style="background: linear-gradient(135deg, {{ $settings['primary_color'] }} 0%, {{ $settings['secondary_color'] }} 100%);"
                            id="saveBtn">
                        <i class="fas fa-save mr-2"></i> Save Theme
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            var primary = document.getElementById('primary_color');
            var secondary = document.getElementById('secondary_color');
            var primaryText = document.getElementById('primary_color_text');
            var secondaryText = document.getElementById('secondary_color_text');
            var preview = document.getElementById('live_preview');
            var saveBtn = document.getElementById('saveBtn');

            function syncPreview() {
                if (primaryText && primary) primaryText.value = primary.value;
                if (secondaryText && secondary) secondaryText.value = secondary.value;
                if (preview && primary && secondary) {
                    preview.style.background = 'linear-gradient(135deg, ' + primary.value + ' 0%, ' + secondary.value + ' 100%)';
                }
                if (saveBtn && primary && secondary) {
                    saveBtn.style.background = 'linear-gradient(135deg, ' + primary.value + ' 0%, ' + secondary.value + ' 100%)';
                }
            }

            if (primary) primary.addEventListener('input', syncPreview);
            if (secondary) secondary.addEventListener('input', syncPreview);
            syncPreview();

            var logoInput = document.getElementById('hospital_logo');
            var logoWrap = document.getElementById('logoPreviewWrap');
            var logoImg = document.getElementById('logoPreviewImg');
            if (logoInput) {
                logoInput.addEventListener('change', function () {
                    var file = this.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        logoImg.src = e.target.result;
                        logoWrap.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

            var favInput = document.getElementById('favicon');
            var favWrap = document.getElementById('faviconPreviewWrap');
            var favImg = document.getElementById('faviconPreviewImg');
            if (favInput) {
                favInput.addEventListener('change', function () {
                    var file = this.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        favImg.src = e.target.result;
                        favWrap.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

            window.removeLogo = function () {
                if (logoInput) logoInput.value = '';
                if (logoWrap) logoWrap.classList.add('hidden');
            };
            window.removeFavicon = function () {
                if (favInput) favInput.value = '';
                if (favWrap) favWrap.classList.add('hidden');
            };

            var form = document.getElementById('themeForm');
            if (form) {
                form.addEventListener('submit', function () {
                    var btn = document.getElementById('saveBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                    }
                });
            }
        })();
    </script>
    @endpush
</x-app-layout>
