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
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> Pray with me</p>
                <h1 class="mt-3 app-section-title">
                    {{ $devotional ? 'Pray through this devotion together' : 'Invite someone into prayer' }}
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    {{ $devotional ? 'This public prayer page helps two people share the same devotion, pray, and keep the moment easy to join.' : 'Use this public page when you want someone to pause, pray, and start a guided MannaRise prayer session with you.' }}
                </p>
            </div>

            <div data-prayer-invite-share data-invite-url="{{ $inviteUrl }}" class="app-surface border-rose-200 bg-rose-50 p-4">
                <p class="text-sm font-black uppercase tracking-normal text-rose-900">Share invite</p>
                <div class="mt-3 grid gap-2">
                    <button type="button" data-invite-share="whatsapp" class="btn-secondary w-full border-emerald-200 text-emerald-900 hover:bg-emerald-50"><x-ui.icon name="whatsapp" class="h-4 w-4" /> WhatsApp</button>
                    <button type="button" data-invite-share="copy" class="btn-secondary w-full border-rose-200 hover:bg-white"><x-ui.icon name="link" class="h-4 w-4" /> Copy link</button>
                </div>
                <p data-invite-status class="mt-3 min-h-5 text-sm font-bold text-rose-900"></p>
            </div>
        </div>
    </section>

    <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)] lg:items-start">
        <main class="space-y-5">
            @if ($devotional)
                <article class="app-panel border-amber-200 bg-amber-50">
                    <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Shared devotion</p>
                    <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $devotional->title }}</h2>
                    <p class="mt-2 text-sm font-bold text-amber-900">{{ $devotional->category?->name ?: 'MannaRise devotional' }}</p>
                    <p class="mt-4 text-base leading-7 text-slate-700">{{ $summary }}</p>

                    @if ($devotional->bible_reference || $devotional->bible_text)
                        <div class="mt-5 rounded-2xl border border-amber-200 bg-white p-4">
                            @if ($devotional->bible_reference)
                                <p class="flex items-center gap-2 text-sm font-black text-amber-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $devotional->bible_reference }}</p>
                            @endif
                            @if ($devotional->bible_text)
                                <p class="mt-3 text-sm leading-6 text-slate-700">{{ $devotional->bible_text }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($devotional->prayer_point)
                        <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                            <p class="flex items-center gap-2 text-sm font-black text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> Prayer focus</p>
                            <p class="mt-3 text-sm leading-6 text-slate-700">{{ $devotional->prayer_point }}</p>
                        </div>
                    @endif
                </article>
            @else
                <article class="app-panel border-amber-200 bg-amber-50">
                    <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Shared prayer moment</p>
                    <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">Pause, pray, and continue with God</h2>
                    <p class="mt-4 text-base leading-7 text-slate-700">{{ $summary }}</p>
                </article>
            @endif

            <section class="app-panel border-emerald-200 bg-emerald-50">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="route" class="h-5 w-5 text-emerald-800" /> Start together</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('prayer-sessions.index') }}" class="btn-primary w-full"><x-ui.icon name="heart" class="h-4 w-4" /> Start guided prayer</a>
                    <a href="{{ route('prayer-requests.submit') }}" class="btn-secondary w-full border-emerald-200 hover:bg-white"><x-ui.icon name="send" class="h-4 w-4" /> Submit prayer request</a>
                    @if ($devotional)
                        <a href="{{ route('devotionals.show', $devotional->slug) }}" class="btn-secondary w-full border-amber-200 text-amber-900 hover:bg-amber-50 sm:col-span-2"><x-ui.icon name="book-open" class="h-4 w-4" /> Read the devotion</a>
                    @endif
                </div>
            </section>
        </main>

        <aside class="app-panel border-slate-200 bg-white lg:sticky lg:top-36">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="check-circle" class="h-4 w-4 text-rose-800" /> Prayer rhythm</h2>
            <div class="mt-4 space-y-3 text-sm leading-6 text-slate-700">
                <p class="rounded-2xl bg-rose-50 p-3"><span class="font-black text-slate-950">1. Read:</span> Start with the shared devotion or the daily Scripture.</p>
                <p class="rounded-2xl bg-amber-50 p-3"><span class="font-black text-slate-950">2. Pray:</span> Name one request and one reason for gratitude.</p>
                <p class="rounded-2xl bg-emerald-50 p-3"><span class="font-black text-slate-950">3. Respond:</span> Send encouragement, submit a request, or begin a guided session.</p>
            </div>
        </aside>
    </div>

    <script>
        (() => {
            const root = document.querySelector('[data-prayer-invite-share]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const status = root.querySelector('[data-invite-status]');
            const inviteText = @json($shareText);
            const inviteUrl = root.dataset.inviteUrl || window.location.href;

            function setStatus(message) {
                status.textContent = message;
                window.setTimeout(() => {
                    if (status.textContent === message) {
                        status.textContent = '';
                    }
                }, 3000);
            }

            async function copyInvite() {
                const text = `${inviteText}\n\n${inviteUrl}`.trim();

                if (! navigator.clipboard) {
                    setStatus('Clipboard copy is not available in this browser.');
                    return;
                }

                await navigator.clipboard.writeText(text);
                setStatus('Invite link copied.');
            }

            root.querySelectorAll('[data-invite-share]').forEach((button) => {
                button.addEventListener('click', async () => {
                    if (button.dataset.inviteShare === 'copy') {
                        await copyInvite();
                        return;
                    }

                    window.open(`https://wa.me/?text=${encodeURIComponent(`${inviteText}\n\n${inviteUrl}`)}`, '_blank', 'noopener,noreferrer,width=720,height=640');
                    setStatus('WhatsApp invite opened.');
                });
            });
        })();
    </script>
</div>
