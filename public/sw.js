const CACHE_NAME = 'salonpro-v1';
const URLS_TO_CACHE = [
  '/public/index.php',
  '/public/assets/css/style.css',
  '/public/assets/js/app.js',
  '/public/manifest.webmanifest'
];

self.addEventListener('install', event => {
  event.waitUntil(caches.open(CACHE_NAME).then(cache => cache.addAll(URLS_TO_CACHE)));
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => response || fetch(event.request))
  );
});
