<div class="space-y-6 sm:space-y-8">
    <section class="page-hero border-emerald-200">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow"><x-ui.icon name="download" class="h-4 w-4" /> Offline</p>
                <h1 class="mt-3 app-section-title">Saved for offline</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">MannaRise keeps core reading, prayer, and devotional pages available after you open or save them.</p>
            </div>
            <button type="button" data-cache-core-offline class="btn-primary w-full sm:w-auto"><x-ui.icon name="download" class="h-4 w-4" /> Save core pages</button>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Bible', route('bible'), 'book-open'],
            ['Daily rhythm', route('daily.index'), 'star'],
            ['Guided prayer', route('prayer-sessions.index'), 'heart'],
            ['Devotional plans', route('devotional-plans.index'), 'route'],
        ] as [$label, $url, $icon])
            <a href="{{ $url }}" class="dashboard-action-card">
                <span class="icon-badge text-emerald-800"><x-ui.icon :name="$icon" class="h-5 w-5" /></span>
                <span>
                    <span class="block font-black tracking-normal text-slate-950">{{ $label }}</span>
                    <span class="mt-1 block text-sm leading-6 text-slate-600">Open once while online to refresh offline access.</span>
                </span>
            </a>
        @endforeach
    </section>

    <div data-offline-status class="app-panel border-sky-200 bg-sky-50 text-sm font-bold text-sky-900">Offline status will appear here.</div>

    <script>
        (() => {
            const button = document.querySelector('[data-cache-core-offline]');
            const status = document.querySelector('[data-offline-status]');

            if (! button || button.dataset.ready === 'true') {
                return;
            }

            button.dataset.ready = 'true';
            button.addEventListener('click', () => {
                if (! ('serviceWorker' in navigator) || ! window.caches) {
                    status.textContent = 'Offline saving is not available in this browser.';
                    return;
                }

                const urls = ['/', '/bible', '/daily', '/guided-prayer', '/devotionals', '/plans', '/memory-verses', '/prayer-wall'];

                caches.open('mannarise-offline-reading-v1')
                    .then((cache) => Promise.all(urls.map((url) => cache.add(url).catch(() => null))))
                    .then(() => {
                        navigator.serviceWorker.controller?.postMessage?.({ type: 'CACHE_OFFLINE_URLS', urls });
                        status.textContent = 'Core pages saved for offline use.';
                    })
                    .catch(() => {
                        status.textContent = 'Unable to save offline pages right now.';
                    });
            });
        })();
    </script>
</div>
