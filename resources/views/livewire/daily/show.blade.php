<div class="space-y-6 sm:space-y-8">
    @php
        $previousDate = $date->subDay()->toDateString();
        $nextDate = $date->addDay()->toDateString();
        $previousUrl = $locale ? route('daily.localized.show', ['locale' => $locale, 'date' => $previousDate]) : route('daily.show', ['date' => $previousDate]);
        $nextUrl = $locale ? route('daily.localized.show', ['locale' => $locale, 'date' => $nextDate]) : route('daily.show', ['date' => $nextDate]);
        $chapterUrl = $scripture['reader_url'] ?? null;
    @endphp

    <section class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-400"></span>
        </div>

        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,25rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="star" class="h-4 w-4" /> {{ $copy['page_eyebrow'] }}</p>
                <h1 class="mt-3 app-section-title">{{ $copy['page_title'] }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    {{ $copy['page_intro'] }}
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($languageOptions as $option)
                        <a href="{{ $option['url'] }}" class="inline-flex min-h-9 items-center rounded-full border px-3 py-1.5 text-xs font-black {{ $option['current'] ? 'border-emerald-700 bg-emerald-700 text-white' : 'border-emerald-200 bg-white text-emerald-900 hover:bg-emerald-50' }}">
                            {{ strtoupper($option['code']) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                <a href="{{ $previousUrl }}" class="btn-secondary border-emerald-200 hover:bg-emerald-50"><x-ui.icon name="chevron-left" class="h-4 w-4" /> {{ $copy['previous_day'] }}</a>
                <a href="{{ $nextUrl }}" class="btn-secondary border-emerald-200 hover:bg-emerald-50">{{ $copy['next_day'] }} <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
            </div>
        </div>
    </section>

    <section data-daily-devotion-card wire:ignore class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,26rem)] lg:items-start">
        <main class="space-y-5">
            <article class="app-panel border-blue-200 bg-blue-50">
                <p class="app-eyebrow border-blue-200 bg-white text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $copy['scripture_label'] }}</p>
                <blockquote class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">"{{ $scripture['text'] }}"</blockquote>
                <p class="mt-4 text-sm font-black tracking-normal text-blue-900">{{ $scripture['reference'] }}</p>

                @if ($chapterUrl)
                    <a href="{{ $chapterUrl }}" class="mt-5 btn-secondary border-blue-200 text-blue-900 hover:bg-white">
                        {{ $copy['read_chapter'] }} <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </a>
                @endif
            </article>

            <div class="grid gap-5 md:grid-cols-2">
                <article class="app-panel border-amber-200 bg-amber-50">
                    <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> {{ $copy['affirmation_label'] }}</p>
                    <p class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">{{ $affirmation['text'] }}</p>
                    <p class="mt-4 text-sm font-black tracking-normal text-amber-900">{{ $affirmation['reference'] }}</p>
                </article>

                <article class="app-panel border-rose-200 bg-rose-50">
                    <p class="app-eyebrow border-rose-200 bg-white text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> {{ $copy['prayer_label'] }}</p>
                    <p class="mt-4 text-base font-semibold leading-7 text-slate-800">{{ $reflection['prayer'] }}</p>
                </article>
            </div>

            <article class="app-panel border-violet-200 bg-violet-50">
                <p class="app-eyebrow border-violet-200 bg-white text-violet-900"><x-ui.icon name="journal" class="h-4 w-4" /> {{ $copy['journal_label'] }}</p>
                <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $reflection['journal_prompt'] }}</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $reflection['action'] }}</p>
            </article>
        </main>

        <aside class="space-y-4 lg:sticky lg:top-36">
            <div class="app-panel border-sky-200 bg-sky-50">
                <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="share-2" class="h-4 w-4 text-sky-900" /> {{ $copy['share_title'] }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $copy['share_note'] }}</p>

                <div class="mt-4 grid gap-2">
                    <button type="button" data-daily-card-action="download" class="btn-primary w-full bg-sky-700 hover:bg-sky-800"><x-ui.icon name="download" class="h-4 w-4" /> {{ $copy['download_image'] }}</button>
                    <button type="button" data-daily-card-action="whatsapp" class="btn-secondary w-full border-emerald-200 text-emerald-900 hover:bg-emerald-50"><x-ui.icon name="whatsapp" class="h-4 w-4" /> {{ $copy['whatsapp_share'] }}</button>
                    <button type="button" data-daily-card-action="copy" class="btn-secondary w-full border-sky-200 hover:bg-white"><x-ui.icon name="link" class="h-4 w-4" /> {{ $copy['copy_link'] }}</button>
                    <button type="button" data-daily-card-action="native" class="btn-secondary w-full border-slate-200 hover:bg-white"><x-ui.icon name="share-2" class="h-4 w-4" /> {{ $copy['device_share'] }}</button>
                </div>

                <p data-daily-card-status class="mt-3 min-h-5 text-sm font-bold text-sky-900"></p>
            </div>

            <div class="app-panel border-slate-200 bg-slate-950">
                <canvas data-daily-card-canvas class="block h-auto w-full rounded-xl border border-white/10 bg-white shadow-2xl"></canvas>
            </div>
        </aside>
    </section>

    <script>
        (() => {
            const root = document.querySelector('[data-daily-devotion-card]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const card = @json($card);
            const canvas = root.querySelector('[data-daily-card-canvas]');
            const status = root.querySelector('[data-daily-card-status]');
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

            function strokeRoundRect(x, y, width, height, radius, color, lineWidth = 3) {
                ctx.strokeStyle = color;
                ctx.lineWidth = lineWidth;
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
                ctx.stroke();
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

            function drawCard() {
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
                gradient.addColorStop(0, '#ecfdf5');
                gradient.addColorStop(0.55, '#eff6ff');
                gradient.addColorStop(1, '#fff7ed');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, width, height);

                ['#10b981', '#14b8a6', '#0ea5e9', '#8b5cf6', '#f59e0b', '#f43f5e'].forEach((color, index) => {
                    ctx.fillStyle = color;
                    ctx.fillRect(index * (width / 6), 0, width / 6 + 1, 20);
                });

                fillRoundRect(margin, 88, width - margin * 2, height - 150, 46, '#ffffff');
                strokeRoundRect(panelX, panelY, panelWidth, panelHeight, 34, '#a7f3d0', 4);
                strokeRoundRect(panelX + 16, panelY + 16, panelWidth - 32, panelHeight - 32, 26, '#bfdbfe', 2);

                fillRoundRect(panelX + 44, panelY + 42, 368, 62, 31, '#ecfdf5');
                ctx.fillStyle = '#047857';
                ctx.font = `700 31px ${displayFont}`;
                ctx.textBaseline = 'top';
                ctx.fillText(card.labels.daily_devotion, panelX + 76, panelY + 56);

                ctx.fillStyle = '#0f172a';
                ctx.font = `800 34px ${sansFont}`;
                const dateWidth = Math.min(ctx.measureText(card.date).width + 70, 370);
                fillRoundRect(width - margin - dateWidth - 34, panelY + 42, dateWidth, 62, 31, '#fef3c7');
                ctx.fillStyle = '#0f172a';
                ctx.fillText(card.date, width - margin - dateWidth, panelY + 56);

                ctx.fillStyle = '#0f172a';
                ctx.font = `700 58px ${displayFont}`;
                wrapText(card.title, panelWidth - 104).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, panelX + 58, panelY + 152 + index * 66);
                });

                const scriptureStart = panelY + 310;
                const scripture = fitLines(card.scripture_text, panelWidth - 156, 5, 46, 34);
                const scriptureLineHeight = Math.round(scripture.size * 1.4);

                ctx.fillStyle = '#bfdbfe';
                ctx.font = `700 92px ${displayFont}`;
                ctx.fillText('"', panelX + 44, scriptureStart - 18);
                ctx.fillStyle = '#0f172a';
                ctx.font = `600 ${scripture.size}px ${serifFont}`;
                scripture.lines.forEach((line, index) => {
                    ctx.fillText(line, panelX + 94, scriptureStart + index * scriptureLineHeight);
                });

                const referenceY = scriptureStart + scripture.lines.length * scriptureLineHeight + 42;
                ctx.fillStyle = '#1d4ed8';
                ctx.font = `800 32px ${sansFont}`;
                ctx.fillText(card.scripture_reference, panelX + 94, referenceY);

                const affirmationY = referenceY + 82;
                fillRoundRect(panelX + 58, affirmationY - 26, panelWidth - 116, 152, 28, '#fffbeb');
                ctx.fillStyle = '#92400e';
                ctx.font = `800 25px ${sansFont}`;
                ctx.fillText(card.labels.affirmation, panelX + 94, affirmationY);
                ctx.fillStyle = '#0f172a';
                ctx.font = `600 34px ${serifFont}`;
                wrapText(card.affirmation, panelWidth - 188).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, panelX + 94, affirmationY + 42 + index * 43);
                });

                const prayerY = affirmationY + 192;
                ctx.fillStyle = '#be123c';
                ctx.font = `800 25px ${sansFont}`;
                ctx.fillText(card.labels.prayer, panelX + 58, prayerY);
                ctx.fillStyle = '#0f172a';
                ctx.font = `600 32px ${serifFont}`;
                wrapText(card.prayer, panelWidth - 116).slice(0, 3).forEach((line, index) => {
                    ctx.fillText(line, panelX + 58, prayerY + 42 + index * 41);
                });

                const journalY = prayerY + 188;
                ctx.fillStyle = '#6d28d9';
                ctx.font = `800 25px ${sansFont}`;
                ctx.fillText(card.labels.journal_prompt, panelX + 58, journalY);
                ctx.fillStyle = '#0f172a';
                ctx.font = `600 32px ${serifFont}`;
                wrapText(card.journal_prompt, panelWidth - 116).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, panelX + 58, journalY + 42 + index * 41);
                });

                const footerTop = height - 218;
                fillRoundRect(panelX + 58, footerTop, panelWidth - 116, 2, 1, '#a7f3d0');
                fillRoundRect(panelX + 58, footerTop + 34, 108, 10, 5, '#047857');
                ctx.fillStyle = '#0f172a';
                ctx.font = `700 50px ${displayFont}`;
                ctx.fillText('MannaRise', panelX + 58, footerTop + 62);
                ctx.fillStyle = '#047857';
                ctx.font = `800 30px ${sansFont}`;
                ctx.fillText(card.labels.growth, panelX + 58, footerTop + 118);
                ctx.font = `800 26px ${sansFont}`;
                ctx.fillText(card.app_url, panelX + 58, footerTop + 154);
            }

            function slugify(value, fallback) {
                return String(value || fallback)
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '') || fallback;
            }

            function shareText() {
                return [
                    `${card.title} - ${card.date}`,
                    card.scripture_reference,
                    card.affirmation,
                    card.share_url,
                ].filter(Boolean).join('\n\n');
            }

            function trackCardAction(action) {
                const payload = {
                    language: card.analytics.language,
                    daily_date: card.analytics.daily_date,
                    share_id: card.analytics.share_id,
                    share_channel: action,
                    source: 'daily_card',
                    medium: 'share',
                    campaign: 'daily-devotion',
                    url: card.url,
                    path: window.location.pathname,
                };

                if (window.mannaRiseTrackGrowth) {
                    window.mannaRiseTrackGrowth('shared_card_click', payload);
                    return;
                }

                window.fetch(card.analytics.endpoint, {
                    method: 'POST',
                    keepalive: true,
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': card.analytics.csrf,
                    },
                    body: JSON.stringify({ event_type: 'shared_card_click', ...payload }),
                }).catch(() => {});
            }

            function canvasBlob() {
                return new Promise((resolve) => {
                    canvas.toBlob((blob) => resolve(blob), 'image/png');
                });
            }

            function downloadImage() {
                trackCardAction('download');
                drawCard();

                const link = document.createElement('a');
                link.download = `mannarise-daily-devotion-${slugify(card.date, 'today')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                setStatus(card.status.downloaded);
            }

            async function copyLink() {
                if (! navigator.clipboard) {
                    setStatus(card.status.copy_unavailable);
                    return;
                }

                trackCardAction('copy');
                await navigator.clipboard.writeText(card.share_url);
                setStatus(card.status.copied);
            }

            async function nativeShare() {
                if (! navigator.share) {
                    await copyLink();
                    setStatus(card.status.native_unavailable);
                    return;
                }

                trackCardAction('native');

                const blob = await canvasBlob();
                const file = blob ? new File([blob], `mannarise-daily-${slugify(card.date, 'today')}.png`, { type: 'image/png' }) : null;

                try {
                    if (file && navigator.canShare && navigator.canShare({ files: [file] })) {
                        await navigator.share({ title: card.title, text: shareText(), url: card.share_url, files: [file] });
                    } else {
                        await navigator.share({ title: card.title, text: shareText(), url: card.share_url });
                    }

                    setStatus(card.status.shared);
                } catch (error) {
                    if (error?.name !== 'AbortError') {
                        setStatus(card.status.not_completed);
                    }
                }
            }

            root.querySelectorAll('[data-daily-card-action]').forEach((button) => {
                button.addEventListener('click', async () => {
                    if (button.dataset.dailyCardAction === 'download') {
                        downloadImage();
                        return;
                    }

                    if (button.dataset.dailyCardAction === 'copy') {
                        await copyLink();
                        return;
                    }

                    if (button.dataset.dailyCardAction === 'native') {
                        await nativeShare();
                        return;
                    }

                    trackCardAction('whatsapp');
                    window.open(`https://wa.me/?text=${encodeURIComponent(shareText())}`, '_blank', 'noopener,noreferrer,width=720,height=640');
                    setStatus(card.status.whatsapp);
                });
            });

            drawCard();
        })();
    </script>
</div>
