<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-rose-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-violet-500"></span>
        </div>

        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(17rem,24rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="users" class="h-4 w-4" /> Prayer partner room</p>
                <h1 class="mt-3 app-section-title">Invite someone to pray with you today</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    {{ $room->title }}
                </p>
            </div>

            <div data-prayer-partner-share data-share-url="{{ $shareUrl }}" class="app-surface border-rose-200 bg-rose-50 p-4">
                <p class="text-sm font-black uppercase tracking-normal text-rose-900">Share this room</p>
                <div class="mt-3 grid gap-2">
                    <button type="button" data-partner-share="whatsapp" class="btn-secondary w-full border-emerald-200 text-emerald-900 hover:bg-emerald-50"><x-ui.icon name="whatsapp" class="h-4 w-4" /> WhatsApp</button>
                    <button type="button" data-partner-share="copy" class="btn-secondary w-full border-rose-200 hover:bg-white"><x-ui.icon name="link" class="h-4 w-4" /> Copy link</button>
                </div>
                <p data-partner-status class="mt-3 min-h-5 text-sm font-bold text-rose-900"></p>
            </div>
        </div>
    </section>

    <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)] lg:items-start">
        <main class="space-y-5">
            <article class="app-panel border-amber-200 bg-amber-50">
                <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $room->sourceLabel() }}</p>
                <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $room->title }}</h2>
                <p class="mt-4 text-base leading-7 text-slate-700">{{ $room->summary }}</p>

                @if ($room->source_url)
                    <a href="{{ $room->source_url }}" class="mt-5 inline-flex items-center gap-2 text-sm font-black text-amber-900 hover:text-amber-700">
                        <x-ui.icon name="book-open" class="h-4 w-4" /> Open original devotion
                    </a>
                @endif
            </article>

            @if ($room->scripture_reference || $room->scripture_text)
                <section class="app-panel border-sky-200 bg-sky-50">
                    <p class="app-eyebrow border-sky-200 bg-white text-sky-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Scripture</p>
                    @if ($room->scripture_reference)
                        <h2 class="mt-4 text-xl font-black tracking-normal text-slate-950">{{ $room->scripture_reference }}</h2>
                    @endif
                    @if ($room->scripture_text)
                        <p class="mt-4 text-base leading-7 text-slate-700">{{ $room->scripture_text }}</p>
                    @endif
                </section>
            @endif

            <section class="app-panel border-rose-200 bg-rose-50">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="heart" class="h-5 w-5 text-rose-800" /> Pray together</h2>
                <p class="mt-4 text-base leading-7 text-slate-700">{{ $room->prayer_focus }}</p>
                <button type="button" wire:click="markPrayed" @disabled($prayed) class="btn-primary mt-5 w-full sm:w-auto">
                    <x-ui.icon name="check-circle" class="h-4 w-4" /> {{ $prayed ? 'Prayer marked' : 'I prayed with you' }}
                </button>
                <p class="mt-3 min-h-5 text-sm font-bold text-rose-900">{{ $status }}</p>
            </section>

            @if ($room->journal_prompt)
                <section class="app-panel border-emerald-200 bg-emerald-50">
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="journal" class="h-5 w-5 text-emerald-800" /> Reflect together</h2>
                    <p class="mt-4 text-base leading-7 text-slate-700">{{ $room->journal_prompt }}</p>
                </section>
            @endif
        </main>

        <aside class="app-panel border-slate-200 bg-white lg:sticky lg:top-36">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="users" class="h-4 w-4 text-rose-800" /> Room activity</h2>
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-rose-50 p-3">
                    <p class="text-2xl font-black text-slate-950">{{ number_format($room->visits_count) }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-rose-900">Views</p>
                </div>
                <div class="rounded-2xl bg-emerald-50 p-3">
                    <p class="text-2xl font-black text-slate-950">{{ number_format($room->prayed_count) }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-emerald-900">Prayed</p>
                </div>
            </div>

            <div class="mt-5 space-y-3 text-sm leading-6 text-slate-700">
                <p class="rounded-2xl bg-slate-50 p-3"><span class="font-black text-slate-950">1. Read:</span> Begin with the same Scripture and devotion.</p>
                <p class="rounded-2xl bg-slate-50 p-3"><span class="font-black text-slate-950">2. Pray:</span> Use the prayer focus and add one personal request.</p>
                <p class="rounded-2xl bg-slate-50 p-3"><span class="font-black text-slate-950">3. Mark:</span> Tap the prayer button after you pray.</p>
            </div>
        </aside>
    </div>

    <script>
        (() => {
            const root = document.querySelector('[data-prayer-partner-share]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const status = root.querySelector('[data-partner-status]');
            const shareText = @json($shareText);
            const shareUrl = root.dataset.shareUrl || window.location.href;

            function referralUrl(url, refCode) {
                try {
                    const target = new URL(url, window.location.origin);
                    target.searchParams.set('ref', refCode);

                    return target.toString();
                } catch (error) {
                    const separator = String(url).includes('?') ? '&' : '?';

                    return `${url}${separator}ref=${encodeURIComponent(refCode)}`;
                }
            }

            function urlFor(action) {
                return referralUrl(shareUrl, `share_${action}`);
            }

            function trackShare(action) {
                if (! window.mannaRiseTrackGrowth) {
                    return;
                }

                const targetUrl = urlFor(action);

                try {
                    const target = new URL(targetUrl, window.location.origin);
                    window.mannaRiseTrackGrowth('shared_card_click', {
                        language: target.searchParams.get('lang'),
                        daily_date: target.searchParams.get('daily_date'),
                        share_id: target.searchParams.get('sid'),
                        share_channel: action,
                        ref: `share_${action}`,
                        medium: 'share',
                        campaign: 'prayer-partner-room',
                        url: target.toString(),
                        path: window.location.pathname,
                    });
                } catch (error) {
                    window.mannaRiseTrackGrowth('shared_card_click', {
                        share_channel: action,
                        ref: `share_${action}`,
                        medium: 'share',
                        campaign: 'prayer-partner-room',
                        url: targetUrl,
                        path: window.location.pathname,
                    });
                }
            }

            function setStatus(message) {
                status.textContent = message;
                window.setTimeout(() => {
                    if (status.textContent === message) {
                        status.textContent = '';
                    }
                }, 3000);
            }

            async function copyRoom() {
                const text = `${shareText}\n\n${urlFor('copy')}`.trim();

                if (! navigator.clipboard) {
                    setStatus('Clipboard copy is not available in this browser.');
                    return;
                }

                trackShare('copy');
                await navigator.clipboard.writeText(text);
                setStatus('Prayer room link copied.');
            }

            root.querySelectorAll('[data-partner-share]').forEach((button) => {
                button.addEventListener('click', async () => {
                    if (button.dataset.partnerShare === 'copy') {
                        await copyRoom();
                        return;
                    }

                    trackShare('whatsapp');
                    window.open(`https://wa.me/?text=${encodeURIComponent(`${shareText}\n\n${urlFor('whatsapp')}`)}`, '_blank', 'noopener,noreferrer,width=720,height=640');
                    setStatus('WhatsApp invite opened.');
                });
            });
        })();
    </script>
</div>
