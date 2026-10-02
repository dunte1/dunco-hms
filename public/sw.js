/**
 * Dunco HMS Service Worker
 * PWA Offline Support & Caching
 */

const CACHE_NAME = 'duncohms-v2';
const OFFLINE_URL = '/offline';

// Assets to cache on install
const PRECACHE_ASSETS = [
    '/',
    '/manifest.json',
    '/css/sidebar.css',
    '/js/sidebar.js',
    '/images/pwa/icon-192x192.png',
    '/images/pwa/icon-512x512.png',
    OFFLINE_URL,
];

// Install event - precache critical assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(PRECACHE_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName !== CACHE_NAME)
                    .map((cacheName) => caches.delete(cacheName))
            );
        }).then(() => self.clients.claim())
    );
});

function isStorageUrl(url) {
    return url.includes('/storage/');
}

function networkFirst(request) {
    return fetch(request).then((response) => {
        if (response && response.ok) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
                cache.put(request, responseClone);
            });
        }
        return response;
    }).catch(() => {
        return caches.match(request).then((cached) => {
            return cached || caches.match(OFFLINE_URL) || new Response('Offline', { status: 503 });
        });
    });
}

function cacheFirst(request) {
    return caches.match(request).then((cached) => {
        if (cached && cached.ok) return cached;
        return fetch(request).then((response) => {
            if (response && response.ok) {
                const responseClone = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(request, responseClone);
                });
            }
            return response;
        });
    }).catch(() => caches.match(OFFLINE_URL));
}

// Fetch event - serve from cache, fall back to network
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip external URLs
    if (!request.url.startsWith(self.location.origin)) return;

    // Skip API calls and dynamic routes
    if (request.url.includes('/api/') || request.url.includes('/login')) return;

    // Uploaded files (storage) always hit network first — never serve stale errors
    if (isStorageUrl(request.url)) {
        event.respondWith(networkFirst(request));
        return;
    }

    // Network-first strategy for HTML pages
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(networkFirst(request));
        return;
    }

    // Cache-first strategy for static assets (skip non-OK)
    if (request.url.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf)$/)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // Stale-while-revalidate for everything else
    event.respondWith(
        caches.match(request)
            .then((cached) => {
                const fetchPromise = fetch(request).then((response) => {
                    if (response && response.ok) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return response;
                });
                return (cached && cached.ok) ? cached : fetchPromise;
            })
            .catch(() => caches.match(OFFLINE_URL))
    );
});

// Listen for skip-waiting message
self.addEventListener('message', (event) => {
    if (event.data === 'skip-waiting') {
        self.skipWaiting();
    }
});
