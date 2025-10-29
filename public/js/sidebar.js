/**
 * ================================================
 * DUNCOHMS SIDEBAR NAVIGATION
 * Professional Medical Dashboard Navigation Logic
 * ================================================
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('sidebarNav', () => ({
        // State
        openMenus: {},
        currentTopMenu: '',
        
        // Top-level menu identifiers (fixed to match actual menu IDs)
        topLevelMenus: [
            'dashboard',
            'hospital-management',
            'clinical',
            'diagnostics',
            'pharmacy-inventory',
            'finance',
            'hr',
            'reports',
            'communication',
            'settings',
            'cms',
            'ai-integrations'
        ],
        
        /**
         * Initialize sidebar state
         */
        init() {
            this.loadState();
            this.handleWindowResize();
            this.autoOpenActiveMenu();
            window.addEventListener('resize', () => this.handleWindowResize());
        },
        
        /**
         * Load saved state from localStorage
         */
        loadState() {
            try {
                const saved = localStorage.getItem('sidebar-state');
                if (saved) {
                    const state = JSON.parse(saved);
                    this.openMenus = state.openMenus || {};
                    this.currentTopMenu = state.currentTopMenu || '';
                }
            } catch (e) {
                console.warn('Failed to load sidebar state:', e);
            }
        },
        
        /**
         * Save current state to localStorage
         */
        saveState() {
            try {
                const state = {
                    openMenus: this.openMenus,
                    currentTopMenu: this.currentTopMenu
                };
                localStorage.setItem('sidebar-state', JSON.stringify(state));
            } catch (e) {
                console.warn('Failed to save sidebar state:', e);
            }
        },
        
        /**
         * Handle window resize
         */
        handleWindowResize() {
            if (window.innerWidth < 768) {
                // On mobile, close all menus by default
                this.closeAllMenus();
            }
        },
        
        /**
         * Toggle menu open/closed state
         * @param {string} menuId - The menu identifier
         * @param {boolean} isTopLevel - Whether this is a top-level menu
         */
        toggleMenu(menuId, isTopLevel = false) {
            // If this is a top-level menu, close all other top-level menus first
            if (isTopLevel) {
                this.closeAllTopLevelMenus();
                this.currentTopMenu = this.openMenus[menuId] ? '' : menuId;
            }
            
            // Toggle the specific menu
            this.openMenus[menuId] = !this.openMenus[menuId];
            
            // If closing a top-level menu, close all its submenus
            if (isTopLevel && !this.openMenus[menuId]) {
                this.closeSubmenusOf(menuId);
            }
            
            this.saveState();
        },
        
        /**
         * Close all top-level menus
         */
        closeAllTopLevelMenus() {
            this.topLevelMenus.forEach(menuId => {
                this.openMenus[menuId] = false;
            });
                this.currentTopMenu = '';
        },
        
        /**
         * Close all menus
         */
        closeAllMenus() {
            this.openMenus = {};
            this.currentTopMenu = '';
            this.saveState();
        },
        
        /**
         * Close submenus of a specific menu
         * @param {string} parentMenuId - The parent menu ID
         */
        closeSubmenusOf(parentMenuId) {
            // Define submenu relationships
            const submenuMap = {
                'hospital-management': ['patients', 'doctors', 'nurses', 'receptionists', 'ambulance'],
                'clinical': ['bed-management'],
                'diagnostics': ['pathology', 'radiology', 'blood-bank'],
                'pharmacy-inventory': ['medicines', 'inventory'],
                'finance': ['financial-reports', 'billing', 'payments', 'advance-payments', 'accounts', 'expenses', 'income', 'insurance'],
                'hr': ['employees', 'payrolls', 'attendance', 'hr-documents'],
                'communication': ['appointments', 'queue', 'enquiries', 'notices', 'messaging', 'reminders'],
                'settings': ['general-settings', 'user-management'],
                'ai-integrations': ['integrations']
            };
            
            const submenus = submenuMap[parentMenuId] || [];
            submenus.forEach(submenuId => {
                this.openMenus[submenuId] = false;
            });
        },
        
        /**
         * Check if a menu is open
         * @param {string} menuId - The menu identifier
         * @returns {boolean} - Whether the menu is open
         */
        isMenuOpen(menuId) {
            return !!this.openMenus[menuId];
        },
        
        /**
         * Auto-open menu based on current page
         */
        autoOpenActiveMenu() {
            // Find active menu items
            const activeLinks = document.querySelectorAll('.submenu-link.active, .nested-link.active');
            
            if (activeLinks.length > 0) {
                const activeLink = activeLinks[0];
                const menuPath = this.getMenuPath(activeLink);
                
                if (menuPath.length > 0) {
                    // Open the menu path
                    this.openMenuPath(menuPath);
                }
            }
        },
        
        /**
         * Get the menu path for an active link
         * @param {Element} activeLink - The active link element
         * @returns {Array} - Array of menu IDs in the path
         */
        getMenuPath(activeLink) {
            const menuPath = [];
            let element = activeLink;
            
            // Traverse up the DOM to find menu containers
            while (element && element !== document.body) {
                const menuId = this.extractMenuIdFromElement(element);
                if (menuId) {
                    menuPath.unshift(menuId);
                }
                element = element.parentElement;
            }
            
            return menuPath;
        },
        
        /**
         * Extract menu ID from element's x-show attribute
         * @param {Element} element - The element to check
         * @returns {string|null} - The menu ID or null
         */
        extractMenuIdFromElement(element) {
            const xShow = element.getAttribute('x-show');
            if (xShow) {
                const match = xShow.match(/isMenuOpen\('(.+?)'\)/);
                if (match) {
                    return match[1];
                }
            }
            return null;
        },
        
        /**
         * Open a specific menu path
         * @param {Array} menuPath - Array of menu IDs to open
         */
        openMenuPath(menuPath) {
            // Close all menus first
            this.closeAllMenus();
            
            // Open the menu path
            menuPath.forEach((menuId, index) => {
                this.openMenus[menuId] = true;
                
                // Set current top menu if this is a top-level menu
                if (this.topLevelMenus.includes(menuId)) {
                    this.currentTopMenu = menuId;
                }
            });
            
            this.saveState();
        },
        
        /**
         * Highlight active menu based on current route
         * This should be called when route changes
         */
        highlightActiveMenu() {
            // Implementation depends on your routing system
            // You can use Laravel's request()->routeIs() in Blade
            // Or implement client-side route matching here
        }
    }));
});

/**
 * Utility: Auto-open menu based on current page
 * Call this after page load to open the menu containing the active link
 */
function autoOpenActiveMenu() {
    const activeLink = document.querySelector('.submenu-link.active, .nested-link.active');
    
    if (activeLink) {
        // Find parent menus and open them
        let parent = activeLink.closest('[x-show]');
        const menusToOpen = [];
        
        while (parent) {
            const menuId = parent.getAttribute('x-show')?.match(/isMenuOpen\('(.+?)'\)/)?.[1];
            if (menuId) {
                menusToOpen.unshift(menuId);
            }
            parent = parent.parentElement?.closest('[x-show]');
        }
        
        // Dispatch event to open the menu path
        if (menusToOpen.length > 0) {
            window.dispatchEvent(new CustomEvent('open-menu-path', {
                detail: { path: menusToOpen }
            }));
        }
    }
}

// Auto-open active menu on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoOpenActiveMenu);
} else {
    autoOpenActiveMenu();
}

// Close all menus when clicking outside (mobile)
document.addEventListener('click', (e) => {
    if (window.innerWidth < 768 && !e.target.closest('.sidebar-container')) {
        window.dispatchEvent(new Event('close-all-menus'));
    }
});

// Listen for custom events
window.addEventListener('open-menu-path', (e) => {
    const sidebarComponent = Alpine.$data(document.querySelector('[x-data="sidebarNav()"]'));
    if (sidebarComponent && e.detail.path) {
        sidebarComponent.openMenuPath(e.detail.path);
    }
});

window.addEventListener('close-all-menus', () => {
    const sidebarComponent = Alpine.$data(document.querySelector('[x-data="sidebarNav()"]'));
    if (sidebarComponent) {
        sidebarComponent.closeAllMenus();
    }
});

/**
 * Debug helper
 */
window.debugSidebar = function() {
    const sidebarComponent = Alpine.$data(document.querySelector('[x-data="sidebarNav()"]'));
    console.log('Sidebar State:', {
        openMenus: sidebarComponent?.openMenus,
        currentTopMenu: sidebarComponent?.currentTopMenu,
        topLevelMenus: sidebarComponent?.topLevelMenus
    });
};