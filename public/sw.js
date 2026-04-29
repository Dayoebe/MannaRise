const CACHE_NAME = 'mannarise-v2';
const STATIC_CACHE_NAME = 'mannarise-static-v2';
const OFFLINE_URL = '/offline.html';
const CORE_ASSETS = [
    '/',
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/maskable-512.png',
    '/icons/apple-touch-icon.png',
    '/icons/favicon-32.png',
    '/icons/favicon-16.png',
    '/icons/icon-192.svg',
    '/icons/icon-512.svg'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE_NAME).then((cache) => Promise.all(
            CORE_ASSETS.map((asset) => cache.add(new Request(asset, { cache: 'reload' })).catch(() => null))
        ))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        Promise.all([
            self.registration.navigationPreload?.enable?.(),
            caches.keys().then((keys) => Promise.all(
                keys
                    .filter((key) => ! [CACHE_NAME, STATIC_CACHE_NAME].includes(key))
                    .map((key) => caches.delete(key))
            )),
        ])
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(
            Promise.resolve(event.preloadResponse)
                .then((preloadResponse) => preloadResponse || fetch(request))
                .then((response) => {
                    if (response?.ok) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                    }

                    return response;
                })
                .catch(() => caches.match(request).then((cached) => cached || caches.match(OFFLINE_URL)))
        );
        return;
    }

    const cacheFirstDestinations = ['style', 'script', 'image', 'font', 'manifest'];

    if (cacheFirstDestinations.includes(request.destination)) {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetch(request).then((response) => {
                if (response.ok || response.type === 'opaque') {
                    const responseClone = response.clone();
                    caches.open(STATIC_CACHE_NAME).then((cache) => cache.put(request, responseClone));
                }

                return response;
            }).catch(() => cached))
        );
        return;
    }

    event.respondWith(fetch(request).catch(() => caches.match(request)));
});
