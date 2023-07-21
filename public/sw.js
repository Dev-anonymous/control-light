const VERSION = "v2";
const BASE_URL = location.protocol + "//" + location.host;

self.addEventListener("install", event => {
    self.skipWaiting();
    event.waitUntil(
        (async () => {
            const cache = await caches.open(VERSION);
            await cache.addAll(["/assets/img/offline.png", "/assets/css/app.min.css", "/offline"]);
        })()
    );
});
self.addEventListener("activate", event => {
    clients.claim();
    event.waitUntil(
        (async () => {
            const keys = await caches.keys();
            await Promise.all(
                keys.map(key => {
                    if (!key.includes(VERSION)) {
                        return caches.delete(key);
                    }
                })
            );
        })()
    );
});
self.addEventListener("fetch", event => {
    if (event.request.mode == "navigate") {
        event.respondWith(
            (async () => {
                try {
                    const req = await fetch(event.request);
                    caches.open(VERSION).then(cache => {
                        cache.add(req.url);
                    });
                    return req;
                } catch (error) {
                    const cache = await caches.open(VERSION);
                    const req = await cache.match(event.request);
                    const off = await cache.match("/offline");
                    return req ?? off;
                }
            })()
        );
    }
    if (
        ["script", "image", "font", "style"].includes(
            event.request.destination
        ) &&
        event.request.url.indexOf(BASE_URL) > -1
    ) {
        event.respondWith(
            (async () => {
                try {
                    const req = await fetch(event.request);
                    caches.open(VERSION).then(cache => {
                        cache.add(req.url);
                    });
                    return req;
                } catch (error) {
                    const cache = await caches.open(VERSION);
                    const req = await cache.match(event.request);
                    var nr = new Request("Oops 404");
                    return req ?? nr
                }
            })()
        );
    }
});
