<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-sky-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-sky-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-indigo-500"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,24rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="image" class="h-4 w-4" /> Scripture and note cards</p>
                <h1 class="mt-3 app-section-title">Shareable Scripture and note cards</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Generate image cards for the verse of the day, affirmations, devotionals, prayers, testimonies, and personal notes.</p>
            </div>
            <div class="app-surface grid grid-cols-3 gap-3 border-emerald-200 bg-emerald-50 p-4 text-center">
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">6</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-emerald-900">Sources</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">3</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-emerald-900">Sizes</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">4</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-emerald-900">Styles</p>
                </div>
            </div>
        </div>
    </section>

    <section data-card-generator wire:ignore class="grid gap-5 lg:grid-cols-[minmax(0,24rem)_minmax(0,1fr)] lg:items-start">
        <div class="app-panel border-sky-200 bg-sky-50">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="settings" class="h-4 w-4 text-sky-900" /> Card builder</h2>

            <div class="mt-4 space-y-4">
                <label class="block">
                    <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="filter" class="h-4 w-4 text-sky-900" /> Source</span>
                    <select data-card-type class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100">
                        <option value="verse">Verse of the day</option>
                        <option value="affirmation">Affirmation</option>
                        <option value="devotional">Devotional</option>
                        <option value="prayer">Prayer</option>
                        <option value="testimony">Testimony</option>
                        <option value="note">Note card</option>
                    </select>
                </label>

                <label data-card-item-wrap class="block">
                    <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="bookmark" class="h-4 w-4 text-sky-900" /> Content</span>
                    <select data-card-item class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100"></select>
                </label>

                <div data-note-fields class="hidden space-y-3 rounded-2xl border border-sky-200 bg-white p-3 shadow-sm">
                    <label class="block">
                        <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="bookmark" class="h-4 w-4 text-sky-900" /> Note title</span>
                        <input type="text" data-note-title maxlength="54" value="Personal note" class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100">
                    </label>

                    <label class="block">
                        <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="journal" class="h-4 w-4 text-sky-900" /> What is on your mind?</span>
                        <textarea data-note-body rows="7" maxlength="420" placeholder="Type your note here..." class="field-input resize-y border-sky-300 focus:border-sky-600 focus:ring-sky-100"></textarea>
                    </label>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                    <label class="block">
                        <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="image" class="h-4 w-4 text-sky-900" /> Size</span>
                        <select data-card-size class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100">
                            <option value="square">Square 1:1</option>
                            <option value="portrait" selected>Portrait 4:5</option>
                            <option value="story">Story 9:16</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="sparkles" class="h-4 w-4 text-sky-900" /> Style</span>
                        <select data-card-style class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100">
                            <option value="harvest">Harvest</option>
                            <option value="river">River</option>
                            <option value="bloom">Bloom</option>
                            <option value="olive">Olive</option>
                        </select>
                    </label>
                </div>

                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                    <button type="button" data-card-download class="btn-primary w-full bg-sky-700 hover:bg-sky-800"><x-ui.icon name="download" class="h-4 w-4" /> Download image</button>
                    <button type="button" data-card-copy class="btn-secondary w-full border-sky-200 hover:bg-white"><x-ui.icon name="copy" class="h-4 w-4" /> Copy image</button>
                </div>

                <details class="group rounded-2xl border border-sky-200 bg-white p-2 shadow-sm">
                    <summary class="btn-secondary w-full cursor-pointer list-none border-sky-200 hover:bg-sky-50 [&::-webkit-details-marker]:hidden">
                        <x-ui.icon name="share-2" class="h-4 w-4" />
                        <span>Share</span>
                        <x-ui.icon name="chevron-right" class="ml-auto h-4 w-4 transition group-open:rotate-90" />
                    </summary>

                    <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                        <button type="button" data-card-share="native" class="btn-secondary w-full border-sky-200 px-3 hover:bg-sky-50"><x-ui.icon name="share-2" class="h-4 w-4" /> Device share</button>
                        <button type="button" data-card-share="whatsapp" class="btn-secondary w-full border-emerald-200 px-3 text-emerald-900 hover:bg-emerald-50"><x-ui.icon name="whatsapp" class="h-4 w-4" /> WhatsApp</button>
                        <button type="button" data-card-share="facebook" class="btn-secondary w-full border-blue-200 px-3 text-blue-900 hover:bg-blue-50"><x-ui.icon name="facebook" class="h-4 w-4" /> Facebook</button>
                        <button type="button" data-card-share="twitter" class="btn-secondary w-full border-slate-300 px-3 hover:bg-slate-50"><x-ui.icon name="x-twitter" class="h-4 w-4" /> X</button>
                        <button type="button" data-card-share="linkedin" class="btn-secondary w-full border-sky-200 px-3 text-sky-900 hover:bg-sky-50"><x-ui.icon name="linkedin" class="h-4 w-4" /> LinkedIn</button>
                        <button type="button" data-card-share="copy-link" class="btn-secondary w-full border-amber-200 px-3 text-amber-900 hover:bg-amber-50"><x-ui.icon name="link" class="h-4 w-4" /> Copy link</button>
                        <a href="{{ route('prayer-invites.show') }}" class="btn-secondary w-full border-rose-200 px-3 text-rose-900 hover:bg-rose-50 xl:col-span-2"><x-ui.icon name="heart" class="h-4 w-4" /> Invite prayer</a>
                    </div>
                </details>

                <p data-card-status class="min-h-5 text-sm font-bold text-sky-900"></p>
            </div>
        </div>

        <div class="app-panel border-slate-200 bg-slate-950">
            <div class="mx-auto max-w-[34rem]">
                <canvas data-card-canvas class="block h-auto w-full rounded-xl border border-white/10 bg-white shadow-2xl"></canvas>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const root = document.querySelector('[data-card-generator]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const cards = @json($cards);
            const typeSelect = root.querySelector('[data-card-type]');
            const itemField = root.querySelector('[data-card-item-wrap]');
            const itemSelect = root.querySelector('[data-card-item]');
            const noteFields = root.querySelector('[data-note-fields]');
            const noteTitleInput = root.querySelector('[data-note-title]');
            const noteBodyInput = root.querySelector('[data-note-body]');
            const sizeSelect = root.querySelector('[data-card-size]');
            const styleSelect = root.querySelector('[data-card-style]');
            const canvas = root.querySelector('[data-card-canvas]');
            const status = root.querySelector('[data-card-status]');
            const downloadButton = root.querySelector('[data-card-download]');
            const copyButton = root.querySelector('[data-card-copy]');
            const shareButtons = root.querySelectorAll('[data-card-share]');
            const ctx = canvas.getContext('2d');
            const appUrl = @json($appUrl);
            const shareUrl = window.location.href.split('#')[0];
            const sansFont = '"Source Sans 3", Arial, sans-serif';
            const serifFont = 'Lora, Georgia, serif';
            const displayFont = 'Cinzel, Lora, Georgia, serif';

            const sizes = {
                square: { width: 1080, height: 1080 },
                portrait: { width: 1080, height: 1350 },
                story: { width: 1080, height: 1920 },
            };

            const palettes = {
                harvest: {
                    background: '#fff7ed',
                    panel: '#fffbeb',
                    soft: '#fed7aa',
                    ink: '#0f172a',
                    muted: '#92400e',
                    accent: '#f59e0b',
                    accentTwo: '#047857',
                    glow: '#fef3c7',
                    line: '#f3c98b',
                    strip: ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#0ea5e9', '#a855f7'],
                },
                river: {
                    background: '#ecfeff',
                    panel: '#f8fafc',
                    soft: '#bae6fd',
                    ink: '#0f172a',
                    muted: '#0f766e',
                    accent: '#0ea5e9',
                    accentTwo: '#84cc16',
                    glow: '#cffafe',
                    line: '#7dd3fc',
                    strip: ['#0ea5e9', '#14b8a6', '#22c55e', '#eab308', '#f97316', '#ec4899'],
                },
                bloom: {
                    background: '#fff1f2',
                    panel: '#ffffff',
                    soft: '#fbcfe8',
                    ink: '#111827',
                    muted: '#9f1239',
                    accent: '#e11d48',
                    accentTwo: '#7c3aed',
                    glow: '#ffe4e6',
                    line: '#f9a8d4',
                    strip: ['#e11d48', '#ec4899', '#a855f7', '#6366f1', '#0ea5e9', '#10b981'],
                },
                olive: {
                    background: '#f7f8ee',
                    panel: '#ffffff',
                    soft: '#dcdda8',
                    ink: '#1f2937',
                    muted: '#535622',
                    accent: '#8c8f3a',
                    accentTwo: '#d97706',
                    glow: '#ecedd2',
                    line: '#c8c978',
                    strip: ['#8c8f3a', '#22c55e', '#06b6d4', '#f59e0b', '#ef4444', '#8b5cf6'],
                },
            };

            function displayDate() {
                return new Date().toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' });
            }

            function noteCard() {
                const base = (cards.note || [])[0] || {};
                const title = String(noteTitleInput?.value || '').trim() || 'Personal note';
                const text = String(noteBodyInput?.value || '').trim();

                return {
                    ...base,
                    label: 'Note card',
                    title,
                    text: text || base.text || 'Type what is on your mind, then download your MannaRise note card.',
                    reference: 'MannaRise note',
                    date: displayDate(),
                    kind: 'Note',
                };
            }

            function currentCard() {
                if (typeSelect.value === 'note') {
                    return noteCard();
                }

                const list = cards[typeSelect.value] || [];

                return list[Number(itemSelect.value)] || list[0] || {
                    title: 'MannaRise',
                    text: 'The word of God gives light for the next step.',
                    reference: 'Psalm 119:105',
                    date: displayDate(),
                    kind: 'Card',
                };
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

            function fillGradientRect(x, y, width, height, topColor, bottomColor) {
                const gradient = ctx.createLinearGradient(x, y, x, y + height);
                gradient.addColorStop(0, topColor);
                gradient.addColorStop(1, bottomColor);
                ctx.fillStyle = gradient;
                ctx.fillRect(x, y, width, height);
            }

            function drawOrnament(centerX, y, palette) {
                ctx.strokeStyle = palette.accentTwo;
                ctx.lineWidth = 5;
                ctx.beginPath();
                ctx.moveTo(centerX - 130, y);
                ctx.quadraticCurveTo(centerX - 62, y - 32, centerX - 18, y);
                ctx.quadraticCurveTo(centerX, y + 16, centerX + 18, y);
                ctx.quadraticCurveTo(centerX + 62, y - 32, centerX + 130, y);
                ctx.stroke();

                fillRoundRect(centerX - 8, y - 8, 16, 16, 8, palette.accent);
                fillRoundRect(centerX - 162, y - 3, 40, 6, 3, palette.line);
                fillRoundRect(centerX + 122, y - 3, 40, 6, 3, palette.line);
            }

            function populateItems() {
                const list = cards[typeSelect.value] || [];
                itemSelect.innerHTML = '';

                list.forEach((card, index) => {
                    const option = document.createElement('option');
                    option.value = String(index);
                    option.textContent = `${card.label || card.title || `Card ${index + 1}`} · ${card.date || ''}`.trim();
                    itemSelect.appendChild(option);
                });
            }

            function syncSourceControls() {
                const isNote = typeSelect.value === 'note';

                itemField?.classList.toggle('hidden', isNote);
                noteFields?.classList.toggle('hidden', ! isNote);
            }

            function wrapText(text, maxWidth) {
                const words = String(text || '').split(/\s+/).filter(Boolean);
                const lines = [];
                let line = '';

                words.forEach((word) => {
                    if (ctx.measureText(word).width > maxWidth) {
                        if (line) {
                            lines.push(line);
                            line = '';
                        }

                        let chunk = '';

                        Array.from(word).forEach((char) => {
                            const candidate = `${chunk}${char}`;

                            if (ctx.measureText(candidate).width <= maxWidth || ! chunk) {
                                chunk = candidate;
                                return;
                            }

                            lines.push(chunk);
                            chunk = char;
                        });

                        if (chunk) {
                            line = chunk;
                        }

                        return;
                    }

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

            function drawStrip(width, palette) {
                const stripHeight = Math.max(18, Math.round(width * 0.018));
                const bandWidth = width / palette.strip.length;

                palette.strip.forEach((color, index) => {
                    ctx.fillStyle = color;
                    ctx.fillRect(index * bandWidth, 0, bandWidth + 1, stripHeight);
                });
            }

            function draw() {
                const dims = sizes[sizeSelect.value] || sizes.portrait;
                const palette = palettes[styleSelect.value] || palettes.harvest;
                const card = currentCard();
                const width = dims.width;
                const height = dims.height;
                const margin = Math.round(width * 0.07);
                const innerWidth = width - margin * 2;
                const isNoteCard = String(card.kind || '').toLowerCase() === 'note';
                const maxBodyLines = isNoteCard
                    ? (height > 1500 ? 12 : height > 1200 ? 8 : 5)
                    : (height > 1500 ? 10 : height > 1200 ? 6 : 3);
                const bodyStartSize = isNoteCard ? (height > 1500 ? 58 : 52) : (height > 1500 ? 62 : 58);
                const bodyMinSize = isNoteCard ? 34 : 40;
                const panelX = margin + 42;
                const panelY = margin + 84;
                const panelWidth = innerWidth - 84;
                const panelHeight = height - margin * 2 - 140;

                canvas.width = width;
                canvas.height = height;

                fillGradientRect(0, 0, width, height, palette.background, palette.glow);
                drawStrip(width, palette);

                fillRoundRect(margin, margin + 44, innerWidth, height - margin * 2 - 60, 48, palette.panel);
                strokeRoundRect(panelX, panelY, panelWidth, panelHeight, 34, palette.line, 4);
                strokeRoundRect(panelX + 16, panelY + 16, panelWidth - 32, panelHeight - 32, 26, palette.soft, 2);

                fillRoundRect(margin + 24, panelY + 18, 14, panelHeight - 36, 7, palette.accent);
                fillRoundRect(width - margin - 38, panelY + 18, 14, panelHeight - 36, 7, palette.accentTwo);
                fillRoundRect(width - margin - 178, margin + 64, 104, 14, 7, palette.soft);
                fillRoundRect(width - margin - 268, margin + 64, 68, 14, 7, palette.accentTwo);
                drawOrnament(width / 2, panelY + 66, palette);

                fillRoundRect(panelX + 40, panelY + 42, 286, 62, 31, palette.background);
                ctx.fillStyle = palette.muted;
                ctx.font = `700 34px ${displayFont}`;
                ctx.textBaseline = 'top';
                ctx.fillText(String(card.kind || 'Card').toUpperCase(), panelX + 72, panelY + 55);

                ctx.fillStyle = palette.ink;
                ctx.font = `800 36px ${sansFont}`;
                const dateText = card.date || new Date().toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' });
                const dateWidth = Math.min(ctx.measureText(dateText).width + 72, innerWidth - 420);
                fillRoundRect(width - margin - dateWidth - 88, panelY + 42, dateWidth, 62, 31, palette.soft);
                ctx.fillStyle = palette.ink;
                ctx.fillText(dateText, width - margin - dateWidth - 52, panelY + 55);

                ctx.fillStyle = palette.ink;
                ctx.font = `700 58px ${displayFont}`;
                wrapText(card.title || 'MannaRise', innerWidth - 160).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, panelX + 58, panelY + 156 + index * 66);
                });

                const titleLineCount = wrapText(card.title || 'MannaRise', innerWidth - 160).slice(0, 2).length;
                const bodyStart = panelY + 188 + titleLineCount * 72;
                const fitted = fitLines(card.text, panelWidth - 172, maxBodyLines, bodyStartSize, bodyMinSize);
                const lineHeight = Math.round(fitted.size * 1.42);

                ctx.fillStyle = palette.line;
                ctx.font = `700 120px ${displayFont}`;
                ctx.fillText('“', panelX + 42, bodyStart - 22);
                ctx.fillStyle = palette.ink;
                ctx.font = `600 ${fitted.size}px ${serifFont}`;
                fitted.lines.forEach((line, index) => {
                    ctx.fillText(line, panelX + 98, bodyStart + index * lineHeight);
                });

                const referenceY = Math.min(height - margin - 316, bodyStart + fitted.lines.length * lineHeight + 68);
                ctx.fillStyle = palette.muted;
                ctx.font = `700 58px ${displayFont}`;
                wrapText(card.reference || 'MannaRise', panelWidth - 172).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, panelX + 98, referenceY + index * 62);
                });

                const footerTop = height - margin - 218;
                fillRoundRect(panelX + 58, footerTop, panelWidth - 116, 2, 1, palette.line);
                fillRoundRect(panelX + 58, footerTop + 34, 108, 10, 5, palette.accentTwo);
                ctx.fillStyle = palette.ink;
                ctx.font = `700 50px ${displayFont}`;
                ctx.fillText('MannaRise', panelX + 58, footerTop + 62);
                ctx.fillStyle = palette.muted;
                ctx.font = `800 34px ${sansFont}`;
                ctx.fillText('grow daily', panelX + 58, footerTop + 118);
                ctx.font = `800 28px ${sansFont}`;
                ctx.fillText(appUrl, panelX + 58, footerTop + 158);

                fillRoundRect(width - margin - 188, footerTop + 54, 116, 116, 30, palette.background);
                strokeRoundRect(width - margin - 188, footerTop + 54, 116, 116, 30, palette.line, 2);
                ctx.fillStyle = palette.accent;
                ctx.font = `700 62px ${displayFont}`;
                ctx.fillText('MR', width - margin - 160, footerTop + 80);
            }

            function setStatus(message) {
                status.textContent = message;
                window.setTimeout(() => {
                    if (status.textContent === message) {
                        status.textContent = '';
                    }
                }, 3000);
            }

            function validateCardReady() {
                if (typeSelect.value !== 'note' || String(noteBodyInput?.value || '').trim()) {
                    return true;
                }

                setStatus('Type a note before exporting.');
                noteBodyInput?.focus();

                return false;
            }

            function slugify(value, fallback) {
                return String(value || fallback)
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '') || fallback;
            }

            function download() {
                if (! validateCardReady()) {
                    return;
                }

                const link = document.createElement('a');
                const card = currentCard();
                const filename = slugify(card.title, 'mannarise-card');
                const dateSlug = slugify(card.date, '');

                link.download = `${filename}${dateSlug ? `-${dateSlug}` : ''}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                setStatus('PNG downloaded.');
            }

            async function copyImage() {
                if (! validateCardReady()) {
                    return;
                }

                if (! navigator.clipboard || ! window.ClipboardItem) {
                    setStatus('Clipboard image copy is not available in this browser.');
                    return;
                }

                canvas.toBlob(async (blob) => {
                    if (! blob) {
                        setStatus('Unable to copy image.');
                        return;
                    }

                    await navigator.clipboard.write([
                        new ClipboardItem({ 'image/png': blob }),
                    ]);
                    setStatus('Image copied.');
                }, 'image/png');
            }

            function cardShareText() {
                const card = currentCard();
                const text = String(card.text || '').trim();
                const reference = String(card.reference || '').trim();
                const title = String(card.title || 'MannaRise Scripture card').trim();
                const quote = text ? `"${text}"` : title;

                return [quote, reference, 'Shared from MannaRise'].filter(Boolean).join('\n\n');
            }

            function canvasBlob() {
                return new Promise((resolve) => {
                    canvas.toBlob((blob) => resolve(blob), 'image/png');
                });
            }

            async function shareNative() {
                if (! validateCardReady()) {
                    return;
                }

                const card = currentCard();
                const title = String(card.title || 'MannaRise Scripture card');
                const text = cardShareText();

                if (! navigator.share) {
                    await copyShareLink();
                    setStatus('Native sharing is not available, so the share link was copied.');
                    return;
                }

                const blob = await canvasBlob();
                const file = blob ? new File([blob], `${slugify(card.title, 'mannarise-card')}.png`, { type: 'image/png' }) : null;
                const payloadWithFile = file ? { title, text, url: shareUrl, files: [file] } : null;

                try {
                    if (payloadWithFile && navigator.canShare && navigator.canShare({ files: [file] })) {
                        await navigator.share(payloadWithFile);
                    } else {
                        await navigator.share({ title, text, url: shareUrl });
                    }

                    setStatus('Share sheet opened.');
                } catch (error) {
                    if (error?.name !== 'AbortError') {
                        setStatus('Sharing was not completed.');
                    }
                }
            }

            async function copyShareLink() {
                if (! validateCardReady()) {
                    return;
                }

                const text = `${cardShareText()}\n\n${shareUrl}`;

                if (! navigator.clipboard) {
                    setStatus('Clipboard copy is not available in this browser.');
                    return;
                }

                await navigator.clipboard.writeText(text);
                setStatus('Share text and link copied.');
            }

            function openShareWindow(url) {
                window.open(url, '_blank', 'noopener,noreferrer,width=720,height=640');
            }

            async function shareTo(platform) {
                const text = cardShareText();
                const encodedText = encodeURIComponent(text);
                const encodedUrl = encodeURIComponent(shareUrl);

                if (platform === 'native') {
                    await shareNative();
                    return;
                }

                if (platform === 'copy-link') {
                    await copyShareLink();
                    return;
                }

                const urls = {
                    whatsapp: `https://wa.me/?text=${encodedText}%0A%0A${encodedUrl}`,
                    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodedText}`,
                    twitter: `https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedUrl}`,
                    linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`,
                };

                if (urls[platform]) {
                    openShareWindow(urls[platform]);
                    setStatus('Share window opened. Download or copy the image first if you want to attach the PNG.');
                }
            }

            [typeSelect, itemSelect, sizeSelect, styleSelect].forEach((control) => {
                control.addEventListener('change', () => {
                    if (control === typeSelect) {
                        populateItems();
                        syncSourceControls();
                    }

                    draw();
                });
            });

            [noteTitleInput, noteBodyInput].forEach((control) => {
                control?.addEventListener('input', draw);
            });

            downloadButton.addEventListener('click', download);
            copyButton.addEventListener('click', copyImage);
            shareButtons.forEach((button) => {
                button.addEventListener('click', () => shareTo(button.dataset.cardShare));
            });

            populateItems();
            syncSourceControls();
            draw();
        })();
    </script>
</div>
