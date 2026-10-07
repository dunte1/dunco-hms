@php
    /** @var \App\Services\SidebarService $sidebarService */
    $sidebarService = app(\App\Services\SidebarService::class);
    $sidebarSections = $sidebarService->sections();

    $favicon = $themeSettings['favicon'] ?? '';
    $faviconSrc = '';
    if ($favicon) {
        if (str_starts_with($favicon, 'http') || str_starts_with($favicon, 'data:')) {
            $faviconSrc = $favicon;
        } elseif (str_starts_with($favicon, '/storage/')) {
            $faviconSrc = $favicon;
        } else {
            $faviconSrc = '/storage/' . ltrim($favicon, '/');
        }
    }
    $hospitalName = $themeSettings['hospital_name'] ?? config('app.name', 'DuncoHMS');
@endphp

<div class="sidebar-container text-gray-800 dark:text-white h-full shadow-xl border-r border-gray-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900" style="width: 100%; max-width: 280px;" x-data="sidebarNav()">

    <!-- Header -->
    <div class="sidebar-header p-4 border-b" style="background-color: #00001A; border-color: rgba(255,255,255,0.1);">
        <div class="flex items-center justify-between">
            <div class="flex items-center min-w-0">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 shadow-sm flex-shrink-0" style="background: rgba(255,255,255,0.1);">
                    @if($faviconSrc)
                        <img src="{{ $faviconSrc }}" alt="{{ $hospitalName }}" class="w-8 h-8 object-contain rounded">
                    @else
                        <i class="fa fa-hospital text-white text-xl"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    <h1 class="hospital-name text-white font-bold text-sm leading-tight truncate">{{ $hospitalName }}</h1>
                    <p class="hospital-subtitle text-xs" style="color: rgba(255,255,255,0.5);">Healthcare System</p>
                </div>
            </div>
            <button @click="$dispatch('toggle-sidebar-collapse')" class="toggle-btn p-2 rounded-lg transition flex-shrink-0 ml-2 hover:bg-white hover:bg-opacity-15" style="color: rgba(255,255,255,0.6);" title="Collapse/Expand sidebar" aria-label="Collapse sidebar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/><polyline points="16 8 12 12 16 16"/></svg>
            </button>
        </div>
    </div>

    <div class="flex flex-col" style="height: calc(100vh - 104px);">
        <nav class="sidebar-nav mt-2 overflow-y-auto flex-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600" style="max-height: calc(100% - 60px);" aria-label="Main navigation">
            <ul class="space-y-0.5 px-3 pb-4 mb-2">

                @forelse($sidebarSections as $section)
                    @php
                        $menuId = $section['key'];
                        $color = $section['color'] ?? 'emerald';
                        $icon = $section['icon'] ?? 'fa-folder';
                        $sectionLabel = $section['label'] ?? '';
                    @endphp

                    <li class="mb-1">
                        <div class="menu-item menu-item-{{ $color }}" @click="toggleMenu('{{ $menuId }}', true)" role="button" tabindex="0"
                             @keydown.enter.prevent="toggleMenu('{{ $menuId }}', true)"
                             aria-expanded="{{ 'false' }}">
                            <div class="flex items-center min-w-0">
                                <div class="menu-icon menu-icon-{{ $color }}">
                                    <i class="fa {{ $icon }} text-white"></i>
                                </div>
                                <span class="menu-label font-semibold text-gray-700 dark:text-gray-200 truncate">{{ $sectionLabel }}</span>
                            </div>
                            <i class="fa fa-chevron-down text-xs transition-transform text-gray-400 flex-shrink-0"
                               :class="isMenuOpen('{{ $menuId }}') ? 'rotate-180' : ''"></i>
                        </div>

                        <ul x-show="isMenuOpen('{{ $menuId }}')" x-transition class="submenu submenu-{{ $color }}" role="group">
                            @foreach($section['items'] as $item)
                                @if(isset($item['children']))
                                    @php
                                        $childId = $menuId . '-' . \Illuminate\Support\Str::slug($item['label'], '-');
                                    @endphp
                                    <li>
                                        <div class="nested-menu-item" @click="toggleMenu('{{ $childId }}')" role="button" tabindex="0"
                                             @keydown.enter.prevent="toggleMenu('{{ $childId }}')">
                                            <div class="flex items-center min-w-0">
                                                @if(!empty($item['icon']))
                                                    <i class="fa {{ $item['icon'] }} mr-2 w-4 text-gray-400 flex-shrink-0"></i>
                                                @endif
                                                <span class="menu-label truncate">{{ $item['label'] }}</span>
                                            </div>
                                            <i class="fa fa-chevron-down text-[10px] transition-transform text-gray-400 flex-shrink-0"
                                               :class="isMenuOpen('{{ $childId }}') ? 'rotate-180' : ''"></i>
                                        </div>
                                        <ul x-show="isMenuOpen('{{ $childId }}')" x-transition class="nested-submenu" role="group">
                                            @foreach($item['children'] as $child)
                                                @if(!empty($child['route']) && \Illuminate\Support\Facades\Route::has($child['route']))
                                                    <li>
                                                        <a @click.stop
                                                           href="{{ $child['external'] ?? false ? route($child['route']) : route($child['route']) }}"
                                                           @if(!empty($child['external'])) target="_blank" rel="noopener noreferrer" @endif
                                                           class="submenu-link {{ !empty($child['active']) && request()->routeIs($child['active']) ? 'active' : '' }}">
                                                            <i class="fa {{ $child['icon'] ?? 'fa-circle-notch' }} mr-2 w-4 flex-shrink-0"></i>
                                                            <span class="menu-label truncate">{{ $child['label'] }}</span>
                                                            @if(!empty($child['badge']))
                                                                <span class="badge badge-premium">{{ $child['badge'] }}</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @elseif(!empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route']))
                                    <li>
                                        <a @click.stop href="{{ route($item['route']) }}"
                                           @if(!empty($item['external'])) target="_blank" rel="noopener noreferrer" @endif
                                           class="submenu-link {{ !empty($item['active']) && request()->routeIs($item['active']) ? 'active' : '' }}">
                                            <i class="fa {{ $item['icon'] ?? 'fa-circle-notch' }} mr-2 w-4 flex-shrink-0"></i>
                                            <span class="menu-label truncate">{{ $item['label'] }}</span>
                                            @if(!empty($item['badge']))
                                                <span class="badge badge-premium">{{ $item['badge'] }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    @if(!$loop->last)
                        <li class="menu-divider" aria-hidden="true"></li>
                    @endif
                @empty
                    <li class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                        No menu items available for your role.
                    </li>
                @endforelse

            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer border-t p-3 flex-shrink-0" style="height: 60px; background-color: #00001A; border-color: rgba(255,255,255,0.1);">
            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                <div class="flex items-center min-w-0">
                    <i class="fa fa-circle text-green-500 mr-2 animate-pulse flex-shrink-0"></i>
                    <span class="truncate">System Online</span>
                </div>
                <span class="flex-shrink-0">v2.0.1</span>
            </div>
        </div>
    </div>
</div>
