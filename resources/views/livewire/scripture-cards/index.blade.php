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
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="image" class="h-4 w-4" /> Scripture cards</p>
                <h1 class="mt-3 app-section-title">Shareable Scripture cards</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Generate image cards for the verse of the day, affirmations, devotionals, prayers, and testimonies.</p>
            </div>
            <div class="app-surface grid grid-cols-3 gap-3 border-emerald-200 bg-emerald-50 p-4 text-center">
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">5</p>
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
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="bookmark" class="h-4 w-4 text-sky-900" /> Content</span>
                    <select data-card-item class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100"></select>
                </label>

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
                    <button type="button" data-card-download class="btn-primary w-full bg-sky-700 hover:bg-sky-800"><x-ui.icon name="download" class="h-4 w-4" /> Download PNG</button>
                    <button type="button" data-card-copy class="btn-secondary w-full border-sky-200 hover:bg-white"><x-ui.icon name="copy" class="h-4 w-4" /> Copy image</button>
                </div>

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
            const itemSelect = root.querySelector('[data-card-item]');
            const sizeSelect = root.querySelector('[data-card-size]');
            const styleSelect = root.querySelector('[data-card-style]');
            const canvas = root.querySelector('[data-card-canvas]');
            const status = root.querySelector('[data-card-status]');
            const downloadButton = root.querySelector('[data-card-download]');
            const copyButton = root.querySelector('[data-card-copy]');
            const ctx = canvas.getContext('2d');

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
                    strip: ['#8c8f3a', '#22c55e', '#06b6d4', '#f59e0b', '#ef4444', '#8b5cf6'],
                },
            };

            function currentCard() {
                const list = cards[typeSelect.value] || [];

                return list[Number(itemSelect.value)] || list[0] || {
                    title: 'MannaRise',
                    text: 'The word of God gives light for the next step.',
                    reference: 'Psalm 119:105',
                    date: new Date().toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' }),
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
                    ctx.font = `600 ${size}px Lora, Georgia, serif`;
                    const lines = wrapText(text, maxWidth);

                    if (lines.length <= maxLines) {
                        return { size, lines };
                    }
                }

                ctx.font = `600 ${minSize}px Lora, Georgia, serif`;
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
                const maxBodyLines = height > 1500 ? 13 : height > 1200 ? 10 : 8;

                canvas.width = width;
                canvas.height = height;

                ctx.fillStyle = palette.background;
                ctx.fillRect(0, 0, width, height);
                drawStrip(width, palette);

                fillRoundRect(margin, margin + 42, innerWidth, height - margin * 2 - 42, 44, palette.panel);
                fillRoundRect(margin + 28, margin + 70, 16, height - margin * 2 - 98, 8, palette.accent);
                fillRoundRect(width - margin - 132, margin + 70, 76, 14, 7, palette.soft);
                fillRoundRect(width - margin - 210, margin + 70, 58, 14, 7, palette.accentTwo);

                ctx.strokeStyle = palette.soft;
                ctx.lineWidth = 4;
                ctx.strokeRect(margin + 54, margin + 76, innerWidth - 108, height - margin * 2 - 110);

                fillRoundRect(margin + 78, margin + 116, 220, 56, 28, palette.background);
                ctx.fillStyle = palette.muted;
                ctx.font = '800 28px Source Sans 3, Arial, sans-serif';
                ctx.textBaseline = 'top';
                ctx.fillText(String(card.kind || 'Card').toUpperCase(), margin + 108, margin + 128);

                ctx.fillStyle = palette.ink;
                ctx.font = '800 30px Source Sans 3, Arial, sans-serif';
                const dateText = card.date || new Date().toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' });
                const dateWidth = Math.min(ctx.measureText(dateText).width + 56, innerWidth - 330);
                fillRoundRect(width - margin - dateWidth - 78, margin + 116, dateWidth, 56, 28, palette.soft);
                ctx.fillStyle = palette.ink;
                ctx.fillText(dateText, width - margin - dateWidth - 50, margin + 128);

                ctx.fillStyle = palette.ink;
                ctx.font = '800 52px Source Sans 3, Arial, sans-serif';
                wrapText(card.title || 'MannaRise', innerWidth - 160).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, margin + 78, margin + 214 + index * 58);
                });

                const titleLineCount = wrapText(card.title || 'MannaRise', innerWidth - 160).slice(0, 2).length;
                const bodyStart = margin + 248 + titleLineCount * 64;
                const fitted = fitLines(card.text, innerWidth - 190, maxBodyLines, height > 1500 ? 58 : 54, 38);
                const lineHeight = Math.round(fitted.size * 1.45);

                ctx.fillStyle = palette.ink;
                ctx.font = `600 ${fitted.size}px Lora, Georgia, serif`;
                fitted.lines.forEach((line, index) => {
                    ctx.fillText(line, margin + 96, bodyStart + index * lineHeight);
                });

                const referenceY = Math.min(height - margin - 190, bodyStart + fitted.lines.length * lineHeight + 60);
                ctx.fillStyle = palette.muted;
                ctx.font = '800 38px Source Sans 3, Arial, sans-serif';
                wrapText(card.reference || 'MannaRise', innerWidth - 190).slice(0, 2).forEach((line, index) => {
                    ctx.fillText(line, margin + 96, referenceY + index * 42);
                });

                fillRoundRect(margin + 96, height - margin - 126, 96, 10, 5, palette.accentTwo);
                ctx.fillStyle = palette.ink;
                ctx.font = '800 34px Source Sans 3, Arial, sans-serif';
                ctx.fillText('MannaRise', margin + 96, height - margin - 92);
                ctx.fillStyle = palette.muted;
                ctx.font = '700 28px Source Sans 3, Arial, sans-serif';
                ctx.fillText('grow daily', margin + 96, height - margin - 52);

                fillRoundRect(width - margin - 176, height - margin - 114, 98, 98, 24, palette.background);
                ctx.fillStyle = palette.accent;
                ctx.font = '800 44px Source Sans 3, Arial, sans-serif';
                ctx.fillText('MR', width - margin - 146, height - margin - 88);
            }

            function setStatus(message) {
                status.textContent = message;
                window.setTimeout(() => {
                    if (status.textContent === message) {
                        status.textContent = '';
                    }
                }, 3000);
            }

            function download() {
                const link = document.createElement('a');
                const card = currentCard();
                const filename = String(card.title || 'mannarise-card').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'mannarise-card';
                const dateSlug = String(card.date || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');

                link.download = `${filename}${dateSlug ? `-${dateSlug}` : ''}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                setStatus('PNG downloaded.');
            }

            async function copyImage() {
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

            [typeSelect, itemSelect, sizeSelect, styleSelect].forEach((control) => {
                control.addEventListener('change', () => {
                    if (control === typeSelect) {
                        populateItems();
                    }

                    draw();
                });
            });

            downloadButton.addEventListener('click', download);
            copyButton.addEventListener('click', copyImage);

            populateItems();
            draw();
        })();
    </script>
</div>
