<div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,22rem)] lg:items-start">
    <article class="space-y-5">
        <section class="app-panel overflow-hidden border-amber-200 p-0 sm:p-0">
            <div class="color-strip rounded-none">
                <span class="bg-amber-400"></span>
                <span class="bg-yellow-400"></span>
                <span class="bg-lime-500"></span>
                <span class="bg-emerald-500"></span>
                <span class="bg-teal-500"></span>
                <span class="bg-sky-500"></span>
                <span class="bg-violet-500"></span>
            </div>
            <div class="p-5 sm:p-8">
                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 font-bold text-emerald-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $devotional->category?->name }}</span>
                    <span class="rounded-full bg-sky-50 px-3 py-1 font-bold text-sky-900">{{ $devotional->reading_time }} min read</span>
            @if ($devotional->published_at)
                    <span class="rounded-full bg-mist-100 px-3 py-1 font-bold text-mist-800">{{ $devotional->published_at->format('M j, Y') }}</span>
            @endif
                    <span class="rounded-full bg-white px-3 py-1 font-bold text-slate-700">Updated {{ $devotional->updated_at->format('M j, Y') }}</span>
                </div>

                <h1 class="mt-5 text-3xl font-black tracking-normal text-slate-950 sm:text-4xl">{{ $devotional->title }}</h1>
                <p class="mt-4 max-w-3xl rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-base font-semibold leading-7 text-slate-700">{{ $summary }}</p>
                <p class="mt-3 text-sm font-bold text-slate-500">Published by {{ $devotional->author?->name ?: config('seo.site_name') }}</p>

                @if ($devotional->bible_reference || $devotional->bible_text)
                    <blockquote class="mt-6 border-l-4 border-amber-400 bg-amber-50 px-4 py-4">
                        @if ($devotional->bible_reference)
                            <p class="flex items-center gap-2 text-sm font-black text-amber-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $devotional->bible_reference }}</p>
                        @endif
                        @if ($devotional->bible_text)
                            <p class="reading-copy mt-3">{!! nl2br(e($devotional->bible_text)) !!}</p>
                        @endif
                    </blockquote>
                @endif

                <div class="reading-copy mt-8 max-w-none">
                    {!! nl2br(e($devotional->content)) !!}
                </div>
            </div>
        </section>

        @if ($devotional->bible_reference || $devotional->reflection_question || $devotional->prayer_point || $devotional->declaration)
            <section class="app-panel border-emerald-200 bg-emerald-50">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="check-circle" class="h-5 w-5 text-emerald-800" /> Key takeaways</h2>
                <ul class="mt-4 grid gap-3 text-sm leading-6 text-slate-700 md:grid-cols-2">
                    @if ($devotional->bible_reference)
                        <li class="rounded-xl bg-white p-3"><span class="font-black text-slate-950">Scripture:</span> {{ $devotional->bible_reference }}</li>
                    @endif
                    @if ($devotional->reflection_question)
                        <li class="rounded-xl bg-white p-3"><span class="font-black text-slate-950">Reflect:</span> {{ $devotional->reflection_question }}</li>
                    @endif
                    @if ($devotional->prayer_point)
                        <li class="rounded-xl bg-white p-3"><span class="font-black text-slate-950">Pray:</span> {{ $devotional->prayer_point }}</li>
                    @endif
                    @if ($devotional->declaration)
                        <li class="rounded-xl bg-white p-3"><span class="font-black text-slate-950">Declare:</span> {{ $devotional->declaration }}</li>
                    @endif
                </ul>
            </section>
        @endif

        <div class="grid gap-4 md:grid-cols-3">
            @if ($devotional->reflection_question)
                <section class="app-panel border-sky-200 bg-sky-50">
                    <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-sky-950"><x-ui.icon name="journal" class="h-4 w-4" /> Reflection</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $devotional->reflection_question }}</p>
                </section>
            @endif

            @if ($devotional->prayer_point)
                <section class="app-panel border-rose-200 bg-rose-50">
                    <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-rose-950"><x-ui.icon name="heart" class="h-4 w-4" /> Prayer</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $devotional->prayer_point }}</p>
                </section>
            @endif

            @if ($devotional->declaration)
                <section class="app-panel border-violet-200 bg-violet-50">
                    <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-violet-950"><x-ui.icon name="sparkles" class="h-4 w-4" /> Declaration</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $devotional->declaration }}</p>
                </section>
            @endif
        </div>

        @if ($relatedDevotionals->isNotEmpty())
            <section>
                <h2 class="mb-4 text-2xl font-black tracking-normal text-slate-950">Related devotionals</h2>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($relatedDevotionals as $related)
                        <a href="{{ route('devotionals.show', $related->slug) }}" class="app-panel app-panel-hover border-slate-200 bg-white">
                            <p class="text-xs font-black uppercase tracking-normal text-emerald-800">{{ $related->category?->name }}</p>
                            <h3 class="mt-2 font-black tracking-normal text-slate-950">{{ $related->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($related->content), 100) }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>

    <aside class="space-y-4 lg:sticky lg:top-36">
        <div data-devotional-share wire:ignore class="app-panel border-sky-200 bg-sky-50">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="share-2" class="h-4 w-4 text-sky-900" /> Share this devotion</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Public link. No login needed.</p>

            <div class="mt-4 grid gap-2">
                <button type="button" data-devotional-share-action="whatsapp" class="btn-secondary w-full border-emerald-200 text-emerald-900 hover:bg-emerald-50"><x-ui.icon name="whatsapp" class="h-4 w-4" /> WhatsApp share</button>
                <button type="button" data-devotional-share-action="copy" class="btn-secondary w-full border-sky-200 hover:bg-white"><x-ui.icon name="link" class="h-4 w-4" /> Copy link</button>
                <button type="button" data-devotional-share-action="download" class="btn-secondary w-full border-amber-200 text-amber-900 hover:bg-amber-50"><x-ui.icon name="download" class="h-4 w-4" /> Download image</button>
                <a href="{{ $shareCard['invite_url'] }}" data-devotional-share-action="pray" class="btn-primary w-full bg-rose-700 hover:bg-rose-800"><x-ui.icon name="heart" class="h-4 w-4" /> Invite someone to pray with you today</a>
            </div>

            <p data-devotional-share-status class="mt-3 min-h-5 text-sm font-bold text-sky-900"></p>
            <canvas data-devotional-share-canvas class="hidden"></canvas>
        </div>

        <div class="app-panel border-emerald-200 bg-emerald-50">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="star" class="h-4 w-4 text-emerald-800" /> Reading actions</h2>
            <div class="mt-4 space-y-3">
                <button type="button" wire:click="toggleFavorite" class="btn-secondary w-full border-emerald-200">
                    <x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $isFavorited ? 'Remove favorite' : 'Save favorite' }}
                </button>
                <button type="button" wire:click="markCompleted" class="btn-primary w-full">
                    <x-ui.icon name="sparkles" class="h-4 w-4" /> {{ $completedToday ? 'Completed today' : 'Mark completed' }}
                </button>
            </div>
        </div>

        <div class="app-panel border-mauve-200 bg-mauve-50">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="journal" class="h-4 w-4 text-mauve-800" /> Journal reflection</h2>
            @auth
                <form wire:submit="saveJournalEntry" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Title</label>
                        <input type="text" wire:model="journalTitle" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                        @error('journalTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Reflection</label>
                        <textarea wire:model="journalContent" rows="6" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100"></textarea>
                        @error('journalContent') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full bg-mauve-700 hover:bg-mauve-800">Save journal entry</button>
                </form>
            @else
                <p class="mt-3 text-sm leading-6 text-slate-700">Log in to save favorites, track completions, and journal your reflection.</p>
                <a href="{{ route('login') }}" class="mt-4 btn-primary"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in</a>
            @endauth
        </div>
    </aside>

    <script>
        (() => {
            const root = document.querySelector('[data-devotional-share]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const share = @json($shareCard);
            const status = root.querySelector('[data-devotional-share-status]');
            const canvas = root.querySelector('[data-devotional-share-canvas]');
            const ctx = canvas.getContext('2d');
            const sansFont = '"Source Sans 3", Arial, sans-serif';
            const serifFont = 'Lora, Georgia, serif';
            const displayFont = 'Cinzel, Lora, Georgia, serif';

            function setStatus(message) {
                status.textContent = message;
                window.setTimeout(() => {
                    if (status.textContent === message) {
                        status.textContent = '';
                    }
                }, 3000);
            }

            function shareText() {
                return [share.title, share.summary, 'Shared from MannaRise'].filter(Boolean).join('\n\n');
            }

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

            function shareUrlFor(action) {
                return referralUrl(share.url, `share_${action}`);
            }

            function trackShareAction(action, eventType = 'shared_card_click') {
                const refCode = action === 'pray' ? 'pray_with_me' : `share_${action}`;
                const targetUrl = action === 'pray' ? share.invite_url : shareUrlFor(action);
                const payload = {
                    language: share.language,
                    share_id: share.share_id,
                    share_channel: action,
                    ref: refCode,
                    medium: 'share',
                    campaign: 'devotional-share',
                    url: targetUrl,
                    path: window.location.pathname,
                };

                if (window.mannaRiseTrackGrowth) {
                    window.mannaRiseTrackGrowth(eventType, payload);
                    return;
                }

                window.fetch(share.analytics_endpoint, {
                    method: 'POST',
                    keepalive: true,
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': share.csrf,
                    },
                    body: JSON.stringify({ event_type: eventType, ...payload }),
                }).catch(() => {});
            }

            function slugify(value, fallback) {
                return String(value || fallback)
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '') || fallback;
            }

            function fillRoundRect(x, y, width, height, radius, color) {
                ctx.fillStyle = color;
                ctx.beginPath();
                ctx.moveTo(x + radius, y);
                ctx.lineTo(x + width - radius, y);
                ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                ctx.lineTo(x + width, y + height - radius);
                ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
                ctx.lineTo(x + radius, y + height);
                ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                ctx.lineTo(x, y + radius);
                ctx.quadraticCurveTo(x, y, x + radius, y);
                ctx.fill();
            }

            function wrapText(text, maxWidth) {
                const words = String(text || '').split(/\s+/).filter(Boolean);
                const lines = [];
                let line = '';

                words.forEach((word) => {
                    const candidate = line ? `${line} ${word}` : word;

                    if (ctx.measureText(candidate).width <= maxWidth || ! line) {
                        line = candidate;
                        return;
                    }

                    lines.push(line);
                    line = word;
                });

                if (line) {
                    lines.push(line);
                }

                return lines;
            }

            function fitLines(text, maxWidth, maxLines, startSize, minSize) {
                for (let size = startSize; size >= minSize; size -= 2) {
                    ctx.font = `600 ${size}px ${serifFont}`;
                    const lines = wrapText(text, maxWidth);

                    if (lines.length <= maxLines) {
                        return { size, lines };
                    }
                }

                ctx.font = `600 ${minSize}px ${serifFont}`;
                const lines = wrapText(text, maxWidth).slice(0, maxLines);

                if (lines.length === maxLines) {
                    lines[maxLines - 1] = `${lines[maxLines - 1].replace(/[.,;:!?]+$/, '')}...`;
                }

                return { size: minSize, lines };
            }

            function drawImage() {
                const width = 1080;
                const height = 1350;
                const margin = 76;
                const panelX = 118;
                const panelY = 158;
                const panelWidth = width - 236;
                const panelHeight = height - 278;
                const gradient = ctx.createLinearGradient(0, 0, 0, height);

                canvas.width = width;
                canvas.height = height;
                gradient.addColorStop(0, '#fff7ed');
                gradient.addColorStop(1, '#ecfeff');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, width, height);

                ['#f59e0b', '#22c55e', '#0ea5e9', '#8b5cf6', '#e11d48'].forEach((color, index) => {
                    ctx.fillStyle = color;
                    ctx.fillRect(index * (width / 5), 0, width / 5 + 1, 20);
                });

                fillRoundRect(margin, 88, width - margin * 2, height - 150, 46, '#ffffff');
                ctx.strokeStyle = '#f3c98b';
                ctx.lineWidth = 4;
                ctx.strokeRect(panelX, panelY, panelWidth, panelHeight);

                fillRoundRect(panelX + 44, panelY + 42, 304, 62, 31, '#fef3c7');
                ctx.fillStyle = '#92400e';
                ctx.font = `700 32px ${displayFont}`;
                ctx.textBaseline = 'top';
                ctx.fillText('DEVOTIONAL', panelX + 76, panelY + 56);

                ctx.fillStyle = '#0f172a';
                ctx.font = `800 34px ${sansFont}`;
                const dateWidth = Math.min(ctx.measureText(share.date).width + 70, 360);
                fillRoundRect(width - margin - dateWidth - 34, panelY + 42, dateWidth, 62, 31, '#bae6fd');
                ctx.fillStyle = '#0f172a';
                ctx.fillText(share.date, width - margin - dateWidth, panelY + 56);

                ctx.fillStyle = '#0f172a';
                ctx.font = `700 58px ${displayFont}`;
                const titleLines = wrapText(share.title, panelWidth - 104).slice(0, 3);
                titleLines.forEach((line, index) => {
                    ctx.fillText(line, panelX + 58, panelY + 152 + index * 66);
                });

                const bodyStart = panelY + 196 + titleLines.length * 70;
                const fitted = fitLines(share.text || share.summary, panelWidth - 156, 7, 52, 36);
                const lineHeight = Math.round(fitted.size * 1.42);

                ctx.fillStyle = '#f3c98b';
                ctx.font = `700 110px ${displayFont}`;
                ctx.fillText('"', panelX + 44, bodyStart - 24);
                ctx.fillStyle = '#0f172a';
                ctx.font = `600 ${fitted.size}px ${serifFont}`;
                fitted.lines.forEach((line, index) => {
                    ctx.fillText(line, panelX + 94, bodyStart + index * lineHeight);
                });

                const referenceY = Math.min(height - 356, bodyStart + fitted.lines.length * lineHeight + 58);
                ctx.fillStyle = '#0f766e';
                ctx.font = `700 44px ${displayFont}`;
                wrapText(share.reference, panelWidth - 144).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, panelX + 94, referenceY + index * 52);
                });

                const footerTop = height - 236;
                fillRoundRect(panelX + 58, footerTop, panelWidth - 116, 2, 1, '#f3c98b');
                fillRoundRect(panelX + 58, footerTop + 34, 108, 10, 5, '#047857');
                ctx.fillStyle = '#0f172a';
                ctx.font = `700 50px ${displayFont}`;
                ctx.fillText('MannaRise', panelX + 58, footerTop + 62);
                ctx.fillStyle = '#0f766e';
                ctx.font = `800 30px ${sansFont}`;
                ctx.fillText('grow daily', panelX + 58, footerTop + 118);
                ctx.font = `800 26px ${sansFont}`;
                ctx.fillText(share.app_url, panelX + 58, footerTop + 154);
            }

            async function copyLink() {
                if (! navigator.clipboard) {
                    setStatus('Clipboard copy is not available in this browser.');
                    return;
                }

                trackShareAction('copy');
                await navigator.clipboard.writeText(`${shareText()}\n\n${shareUrlFor('copy')}`);
                setStatus('Devotional link copied.');
            }

            function downloadImage() {
                trackShareAction('download');
                drawImage();

                const link = document.createElement('a');
                link.download = `${slugify(share.title, 'mannarise-devotional')}-${slugify(share.date, '')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                setStatus('Image downloaded.');
            }

            root.querySelectorAll('[data-devotional-share-action]').forEach((button) => {
                button.addEventListener('click', async (event) => {
                    if (button.dataset.devotionalShareAction === 'copy') {
                        await copyLink();
                        return;
                    }

                    if (button.dataset.devotionalShareAction === 'download') {
                        downloadImage();
                        return;
                    }

                    if (button.dataset.devotionalShareAction === 'pray') {
                        event.preventDefault();
                        trackShareAction('pray', 'pray_with_me_click');
                        window.location.href = button.href;
                        return;
                    }

                    trackShareAction('whatsapp');
                    window.open(`https://wa.me/?text=${encodeURIComponent(`${shareText()}\n\n${shareUrlFor('whatsapp')}`)}`, '_blank', 'noopener,noreferrer,width=720,height=640');
                    setStatus('WhatsApp share opened.');
                });
            });
        })();
    </script>
</div>
