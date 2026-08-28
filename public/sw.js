const CACHE_NAME = 'budgetin-pwa-v1';
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    '/favicon.svg',
    '/images/logo-icon.svg',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/apple-touch-icon.png'
];

// Install Service Worker and Pre-cache Essential Assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        }).then(() => self.skipWaiting())
    );
});

// Activate & Clear Old Caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Strategy: Network-First for Navigation / HTML & APIs, Cache-First for Static Assets
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Ignore non-GET requests and browser extensions
    if (request.method !== 'GET' || !request.url.startsWith('http')) {
        return;
    }

    // Static Assets (Images, Fonts, CSS, JS from build): Cache-First with Network Fallback
    if (
        request.destination === 'image' ||
        request.destination === 'font' ||
        request.url.includes('/build/') ||
        request.url.includes('/icons/') ||
        request.url.includes('/images/')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // Dynamic Pages (HTML / Data): Network-First with Cache Fallback
    event.respondWith(
        fetch(request)
            .then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200 && request.destination === 'document') {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                }
                return networkResponse;
            })
            .catch(() => {
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Fallback to cached root
                    return caches.match('/');
                });
            })
    );
});
