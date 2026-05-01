<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-blue-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-blue-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $languageLabel }} · {{ $version }}</p>
                <h1 class="mt-3 app-section-title">Bible reader</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Read available Bible translations by book, chapter, language, and version, or search within the selected translation.</p>
            </div>
            <div class="grid gap-2 sm:grid-cols-2 md:flex">
                @if ($lastReading)
                    <a href="{{ route('bible', ['book' => $lastReading->book?->slug, 'chapter' => $lastReading->chapter, 'language' => $lastReading->language, 'version' => $lastReading->version]) }}" class="btn-primary w-full sm:w-auto"><x-ui.icon name="bookmark" class="h-4 w-4" /> Continue reading</a>
                @endif
                <a href="{{ route('library.index') }}" class="btn-secondary w-full border-cyan-200 text-cyan-900 hover:bg-cyan-50 sm:w-auto"><x-ui.icon name="library" class="h-4 w-4" /> Open library</a>
            </div>
        </div>
    </div>

    @if ($books->isEmpty())
        <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600">
            The Bible has not been imported yet. Run `php artisan db:seed --class=BibleSeeder`.
        </div>
    @else
<details class="app-panel bible-filter-panel border-sky-200 bg-sky-50" open>
            <summary class="flex cursor-pointer items-center justify-between gap-3 p-4 text-sm font-bold text-slate-700 sm:hidden">
                <span class="inline-flex items-center gap-2">
                    <x-ui.icon name="book-open" class="h-4 w-4 text-blue-700" /> Bible reader controls
                </span>
                <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-bold text-slate-700">
                    <x-ui.icon name="chevron-down" class="h-4 w-4" /> Toggle
                </span>
            </summary>

            <div class="px-4 pb-4 sm:px-0 sm:pb-0">
                <div class="grid gap-3 sm:gap-4 sm:grid-cols-2 xl:grid-cols-[minmax(0,1fr)_8rem_12rem_12rem_minmax(0,1fr)]">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="book-open" class="h-4 w-4 text-blue-700" /> Book</label>
                        <select wire:model.live="bookSlug" data-bible-change class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                            @foreach ($books as $option)
                                <option value="{{ $option->slug }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="bookmark" class="h-4 w-4 text-blue-700" /> Chapter</label>
                        <select wire:model.live="chapter" data-bible-change class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                            @if ($book)
                                @for ($i = 1; $i <= $chapterCount; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="globe" class="h-4 w-4 text-blue-700" /> Language</label>
                        <select wire:model.live="language" data-bible-change class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                            @foreach ($languages as $translation)
                                <option value="{{ $translation['language'] }}">{{ $translation['language_label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="layers" class="h-4 w-4 text-blue-700" /> Version</label>
                        <select wire:model.live="version" data-bible-change class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                            @foreach ($versions as $translation)
                                <option value="{{ $translation['version'] }}">{{ $translation['version'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-blue-700" /> Search Bible</label>
                        <input type="search" wire:model.live.debounce.400ms="search" placeholder="Search words or phrase" class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                    </div>
                </div>
            </div>
        </details>

        @if ($searchResults)
            <section class="app-panel border-mauve-200 bg-mauve-50">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="search" class="h-5 w-5 text-mauve-700" /> Search results</h2>
                    <span class="inline-flex w-fit rounded-full bg-white px-3 py-1 text-sm font-bold text-mauve-800 shadow-sm">{{ $searchResults->total() }} verses</span>
                </div>

                <div class="space-y-3">
                    @forelse ($searchResults as $result)
                        <article class="rounded-xl border border-mauve-200 bg-white p-3 sm:p-4 shadow-sm">
                            <p class="text-sm font-black tracking-normal text-mauve-800">{{ $result->book->name }} {{ $result->chapter }}:{{ $result->verse }} · {{ strtoupper($result->language) }} {{ $result->version }}</p>
                            <p class="mt-2 text-base leading-7 text-slate-800">{{ $result->text }}</p>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-mauve-300 bg-white p-4 text-sm text-slate-600">No verses matched that search.</p>
                    @endforelse
                </div>

                <div class="mt-5">{{ $searchResults->links() }}</div>
            </section>
        @endif

        @if (session('status'))
            <div class="app-panel border-emerald-200 bg-emerald-50 text-sm font-bold text-emerald-900">
                {{ session('status') }}
            </div>
        @endif

        <article class="app-panel bible-reader-card border-olive-200 bg-white p-4 sm:p-8" data-bible-reader data-bible-language="{{ $language }}">
            <div class="bible-reader-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full border border-olive-200 bg-olive-50 px-3 py-1 text-sm font-black uppercase tracking-normal text-olive-800"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $book?->testament }} · {{ strtoupper($language) }} {{ $version }}</p>
                    <h2 class="mt-3 wrap-break-word text-3xl font-black tracking-normal text-slate-950 sm:text-4xl">{{ $book?->name }} {{ $chapter }}</h2>
                    <p data-bible-audio-status class="mt-2 min-h-5 text-sm font-bold text-olive-800"></p>
                    <p data-bible-share-status class="min-h-5 text-sm font-bold text-sky-800"></p>
                </div>
                <div class="bible-reader-actions grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:justify-end">
                    @auth
                        <button type="button" wire:click="markChapterRead" class="btn-warm col-span-2 sm:col-span-1 px-3">
                            <x-ui.icon name="check-circle" class="h-4 w-4" /> Mark read
                        </button>
                    @endauth
                    <button type="button" data-offline-save data-offline-save-url="{{ request()->fullUrl() }}" class="btn-secondary col-span-2 border-sky-200 px-3 text-sky-900 hover:bg-sky-50 sm:col-span-1">
                        <x-ui.icon name="download" class="h-4 w-4" /> Save offline
                    </button>
                    <button type="button" data-bible-read-chapter data-bible-reference="{{ $book?->name }} {{ $chapter }} {{ $version }}" class="btn-secondary col-span-2 border-olive-300 bg-olive-50 px-3 text-olive-900 hover:bg-olive-100 sm:col-span-1">
                        <x-ui.icon name="volume-2" class="h-4 w-4" /> Listen
                    </button>
                    <button type="button" data-bible-stop class="btn-secondary col-span-2 border-rose-200 px-3 text-rose-900 hover:bg-rose-50 sm:col-span-1">
                        <x-ui.icon name="square" class="h-4 w-4" /> Stop
                    </button>
                    <button type="button" wire:click="previousChapter" data-bible-change class="btn-secondary col-span-1 border-slate-300 px-3">
                        <x-ui.icon name="chevron-left" class="h-4 w-4" /> Previous
                    </button>
                    <button type="button" wire:click="nextChapter" data-bible-change class="btn-primary col-span-1 px-3">
                        Next <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <details class="bible-study-panel mt-6 block rounded-xl border border-indigo-100 bg-indigo-50/70 p-4">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 [&::-webkit-details-marker]:hidden">
                    <span>
                        <span class="app-eyebrow border-indigo-200 bg-white text-indigo-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Study mode</span>
                        <span class="mt-2 block text-lg font-black tracking-normal text-slate-950">Understand {{ $book?->name }} {{ $chapter }}</span>
                    </span>
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-indigo-200 bg-white text-indigo-900">
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </span>
                </summary>

                <div class="mt-5 grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(16rem,0.75fr)]">
                    <div class="space-y-4">
                        <article class="rounded-xl border border-white bg-white p-4">
                            <h3 class="font-black tracking-normal text-slate-950">Chapter summary</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $studyGuide['summary'] }}</p>
                        </article>

                        <article class="rounded-xl border border-white bg-white p-4">
                            <h3 class="font-black tracking-normal text-slate-950">Reflection questions</h3>
                            <div class="mt-3 space-y-2">
                                @foreach ($studyGuide['reflection_questions'] as $question)
                                    <p class="rounded-lg bg-slate-50 p-3 text-sm leading-6 text-slate-700">{{ $question }}</p>
                                @endforeach
                            </div>
                        </article>
                    </div>

                    <aside class="space-y-4">
                        <article class="rounded-xl border border-white bg-white p-4">
                            <h3 class="font-black tracking-normal text-slate-950">Key themes</h3>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($studyGuide['themes'] as $theme)
                                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-indigo-900">{{ $theme }}</span>
                                @endforeach
                            </div>
                        </article>

                        <article class="rounded-xl border border-white bg-white p-4">
                            <h3 class="font-black tracking-normal text-slate-950">Prayer points</h3>
                            <div class="mt-3 space-y-2">
                                @foreach ($studyGuide['prayer_points'] as $point)
                                    <p class="text-sm leading-6 text-slate-700">{{ $point }}</p>
                                @endforeach
                            </div>
                        </article>

                        <article class="rounded-xl border border-white bg-white p-4">
                            <h3 class="font-black tracking-normal text-slate-950">Related devotionals</h3>
                            <div class="mt-3 space-y-2">
                                @forelse ($studyGuide['related_devotionals'] as $devotional)
                                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="block rounded-lg border border-emerald-100 bg-emerald-50 p-3 text-sm font-bold text-emerald-950 hover:border-emerald-300">
                                        {{ $devotional->title }}
                                        <span class="mt-1 block text-xs text-emerald-800">{{ $devotional->category?->name }}</span>
                                    </a>
                                @empty
                                    <p class="text-sm leading-6 text-slate-600">No related devotional found yet. Published devotionals will appear here as your library grows.</p>
                                @endforelse
                            </div>
                        </article>
                    </aside>
                </div>
            </details>

            <div class="reading-copy mt-6 space-y-2">
                @foreach ($verses as $verse)
                    @php
                        $engagement = $engagements[$verse->id] ?? null;
                        $highlight = $engagement?->highlight_color;
                        $highlightClass = match ($highlight) {
                            'emerald' => 'border-emerald-100 bg-emerald-50/70',
                            'sky' => 'border-sky-100 bg-sky-50/70',
                            'rose' => 'border-rose-100 bg-rose-50/70',
                            'violet' => 'border-violet-100 bg-violet-50/70',
                            'amber' => 'border-amber-100 bg-amber-50/70',
                            default => 'border-transparent hover:border-slate-100 hover:bg-slate-50/70',
                        };
                        $reference = "{$book?->name} {$verse->chapter}:{$verse->verse} {$version}";
                        $hasPersonalMark = $engagement?->bookmarked_at || $engagement?->note || $highlight;
                    @endphp
                    <div id="verse-{{ $verse->verse }}" class="group rounded-xl border px-3 py-1 transition {{ $highlightClass }}">
                        <div class="grid grid-cols-[minmax(0,1fr)_2.25rem] gap-2 relative">
                            <p data-bible-verse-text class="min-w-0 self-center py-1.5 leading-7 text-slate-800">
                                <sup class="mr-2 inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-amber-100 px-1 text-xs font-black text-amber-900">{{ $verse->verse }}</sup>{{ $verse->text }}
                            </p>

                            <details class="relative justify-self-end self-start" data-bible-verse-tools>
                                <summary class="mt-1 inline-flex h-8 w-8 cursor-pointer list-none items-center justify-center rounded-full border {{ $hasPersonalMark ? 'border-amber-200 bg-amber-50 text-amber-900' : 'border-transparent bg-transparent text-slate-400 opacity-70 hover:border-slate-200 hover:bg-white hover:text-slate-800 group-hover:opacity-100' }} transition [&::-webkit-details-marker]:hidden" aria-label="Open tools for {{ $reference }}">
                                    <x-ui.icon name="{{ $hasPersonalMark ? 'bookmark' : 'more-horizontal' }}" class="h-4 w-4" />
                                </summary>

                                <div class="bible-verse-tool-panel mt-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm absolute right-0 z-20 w-[min(24rem,85vw)]">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            data-bible-read-verse
                                            data-bible-reference="{{ $reference }}"
                                            data-bible-text="{{ $verse->text }}"
                                            class="btn-secondary min-h-9 border-amber-200 px-3 py-1 text-xs text-amber-900 hover:bg-amber-50"
                                            aria-label="Listen to {{ $book?->name }} {{ $verse->chapter }}:{{ $verse->verse }}"
                                        >
                                            <x-ui.icon name="volume-2" class="h-4 w-4" /> Listen
                                        </button>

                                        @auth
                                            <button type="button" wire:click="toggleBookmark({{ $verse->id }})" class="btn-secondary min-h-9 border-slate-200 px-3 py-1 text-xs {{ $engagement?->bookmarked_at ? 'bg-sky-50 text-sky-900' : '' }}">
                                                <x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $engagement?->bookmarked_at ? 'Saved' : 'Save' }}
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn-secondary min-h-9 border-slate-200 px-3 py-1 text-xs"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to save</a>
                                        @endauth

                                        <button
                                            type="button"
                                            @auth wire:click="recordShare({{ $verse->id }})" @endauth
                                            data-bible-share
                                            data-bible-share-text="{{ $reference }} - {{ $verse->text }}"
                                            data-bible-share-url="{{ route('bible', ['book' => $book?->slug, 'chapter' => $verse->chapter, 'language' => $language, 'version' => $version]).'#verse-'.$verse->verse }}"
                                            class="btn-secondary min-h-9 border-slate-200 px-3 py-1 text-xs"
                                        >
                                            <x-ui.icon name="share-2" class="h-4 w-4" /> Share
                                        </button>
                                    </div>

                                    @auth
                                        <div class="mt-3 flex items-center gap-2">
                                            <span class="text-xs font-black uppercase tracking-normal text-slate-500">Highlight</span>
                                            <div class="flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-1">
                                                @foreach (['amber', 'emerald', 'sky', 'rose', 'violet'] as $color)
                                                    @php
                                                        $swatchClass = match ($color) {
                                                            'emerald' => 'bg-emerald-300',
                                                            'sky' => 'bg-sky-300',
                                                            'rose' => 'bg-rose-300',
                                                            'violet' => 'bg-violet-300',
                                                            default => 'bg-amber-300',
                                                        };
                                                    @endphp
                                                    <button
                                                        type="button"
                                                        wire:click="setHighlight({{ $verse->id }}, '{{ $color }}')"
                                                        class="h-6 w-6 rounded-full border-2 {{ $highlight === $color ? 'border-slate-950' : 'border-white' }} {{ $swatchClass }} shadow-sm"
                                                        aria-label="Highlight {{ $reference }} in {{ $color }}"
                                                    ></button>
                                                @endforeach
                                                @if ($highlight)
                                                    <button type="button" wire:click="setHighlight({{ $verse->id }}, null)" class="ml-1 text-xs font-bold text-slate-500">Clear</button>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="text-xs font-black uppercase tracking-normal text-slate-500">Personal note</label>
                                            <div class="mt-1 grid gap-2 grid-cols-[minmax(0,1fr)_auto]">
                                                <textarea wire:model="notes.{{ $verse->id }}" rows="2" placeholder="What is God showing you in this verse?" class="field-input border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-100"></textarea>
                                                <button type="button" wire:click="saveNote({{ $verse->id }})" class="btn-primary self-start px-3">
                                                    <x-ui.icon name="send" class="h-4 w-4" /> Save
                                                </button>
                                            </div>
                                        </div>
                                    @endauth
                                </div>
                            </details>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>
    @endif

    <script>
        (() => {
            if (window.MannaRiseBibleAudioReady) {
                return;
            }

            window.MannaRiseBibleAudioReady = true;

            let activeUtterance = null;
            let activeReader = null;
            let activeQueue = [];
            let activeQueueIndex = 0;
            let readingStoppedByUser = false;

            function readerFor(element) {
                return element.closest('[data-bible-reader]');
            }

            function statusFor(reader) {
                return reader?.querySelector('[data-bible-audio-status]');
            }

            function shareStatusFor(reader) {
                return reader?.querySelector('[data-bible-share-status]');
            }

            function setStatus(reader, message) {
                const status = statusFor(reader);

                if (status) {
                    status.textContent = message;
                }
            }

            function setShareStatus(reader, message) {
                const status = shareStatusFor(reader);

                if (status) {
                    status.textContent = message;
                    window.setTimeout(() => {
                        if (status.textContent === message) {
                            status.textContent = '';
                        }
                    }, 2500);
                }
            }

            function stopReading(reader = null, message = '') {
                readingStoppedByUser = true;
                activeQueue = [];
                activeQueueIndex = 0;
                activeReader = null;
                activeUtterance = null;

                if (window.speechSynthesis) {
                    window.speechSynthesis.cancel();
                }

                if (reader) {
                    setStatus(reader, message);
                }
            }

            function chapterText(reader, reference) {
                const verses = Array.from(reader.querySelectorAll('[data-bible-read-verse]'))
                    .map((button) => {
                        const verseReference = button.dataset.bibleReference || '';
                        const text = button.dataset.bibleText || '';

                        return `${verseReference}. ${text}`.trim();
                    })
                    .filter(Boolean);

                return `${reference}. ${verses.join(' ')}`.trim();
            }

            function speechLanguage(reader) {
                return {
                    en: 'en-US',
                    es: 'es-ES',
                    fr: 'fr-FR',
                    de: 'de-DE',
                    pt: 'pt-BR',
                    yo: 'yo-NG',
                    ig: 'ig-NG',
                    ha: 'ha-NG',
                    sw: 'sw-KE',
                }[reader?.dataset?.bibleLanguage || 'en'] || 'en-US';
            }

            function speechVoice(language) {
                const voices = window.speechSynthesis?.getVoices?.() || [];
                const baseLanguage = language.split('-')[0].toLowerCase();

                return voices.find((voice) => voice.lang?.toLowerCase() === language.toLowerCase())
                    || voices.find((voice) => voice.lang?.toLowerCase().startsWith(baseLanguage))
                    || voices[0]
                    || null;
            }

            function waitForVoices(callback) {
                if (! window.speechSynthesis?.getVoices) {
                    callback();
                    return;
                }

                if (window.speechSynthesis.getVoices().length > 0) {
                    callback();
                    return;
                }

                let completed = false;
                const run = () => {
                    if (completed) {
                        return;
                    }

                    completed = true;
                    window.speechSynthesis.removeEventListener?.('voiceschanged', run);
                    callback();
                };

                window.speechSynthesis.addEventListener?.('voiceschanged', run);
                window.setTimeout(run, 500);
            }

            function speechChunks(text) {
                const parts = String(text || '')
                    .replace(/\s+/g, ' ')
                    .split(/(?<=[.!?])\s+/)
                    .filter(Boolean);
                const chunks = [];
                let chunk = '';

                parts.forEach((part) => {
                    const candidate = chunk ? `${chunk} ${part}` : part;

                    if (candidate.length <= 220) {
                        chunk = candidate;
                        return;
                    }

                    if (chunk) {
                        chunks.push(chunk);
                    }

                    if (part.length <= 220) {
                        chunk = part;
                        return;
                    }

                    const words = part.split(' ');
                    chunk = '';

                    words.forEach((word) => {
                        const wordCandidate = chunk ? `${chunk} ${word}` : word;

                        if (wordCandidate.length <= 220) {
                            chunk = wordCandidate;
                            return;
                        }

                        if (chunk) {
                            chunks.push(chunk);
                        }

                        chunk = word;
                    });
                });

                if (chunk) {
                    chunks.push(chunk);
                }

                return chunks;
            }

            function speakNext(statusText) {
                if (! activeReader || activeQueueIndex >= activeQueue.length) {
                    const completedReader = activeReader;
                    activeUtterance = null;
                    activeReader = null;
                    activeQueue = [];
                    activeQueueIndex = 0;

                    if (! readingStoppedByUser && completedReader) {
                        setStatus(completedReader, 'Reading complete.');
                    }

                    return;
                }

                const utterance = new SpeechSynthesisUtterance(activeQueue[activeQueueIndex]);
                const language = speechLanguage(activeReader);
                const voice = speechVoice(language);

                if (voice) {
                    utterance.voice = voice;
                    utterance.lang = voice.lang;
                } else {
                    utterance.lang = language;
                }

                utterance.rate = 0.88;
                utterance.pitch = 1;

                utterance.onstart = () => {
                    const progress = activeQueue.length > 1 ? ` ${activeQueueIndex + 1}/${activeQueue.length}` : '';
                    setStatus(activeReader, `${statusText}${progress}`);
                };
                utterance.onend = () => {
                    if (activeUtterance !== utterance) {
                        return;
                    }

                    activeQueueIndex++;
                    activeUtterance = null;
                    speakNext(statusText);
                };
                utterance.onerror = (event) => {
                    if (activeUtterance !== utterance) {
                        return;
                    }

                    const error = event?.error || '';
                    activeUtterance = null;

                    if (readingStoppedByUser || error === 'canceled' || error === 'interrupted') {
                        return;
                    }

                    setStatus(activeReader, error === 'not-allowed'
                        ? 'Tap Listen again to allow Bible audio.'
                        : 'Unable to play Bible audio in this browser.');

                    activeReader = null;
                    activeQueue = [];
                    activeQueueIndex = 0;
                };

                activeUtterance = utterance;
                window.speechSynthesis.speak(utterance);
            }

            function speak(reader, text, statusText) {
                if (! ('speechSynthesis' in window) || ! window.SpeechSynthesisUtterance) {
                    setStatus(reader, 'Bible audio is not available in this browser.');
                    return;
                }

                stopReading();

                activeReader = reader;
                activeQueue = speechChunks(text);
                activeQueueIndex = 0;
                readingStoppedByUser = false;

                if (activeQueue.length === 0) {
                    setStatus(reader, 'No Bible text found to read.');
                    return;
                }

                waitForVoices(() => window.setTimeout(() => speakNext(statusText), 60));
            }

            document.addEventListener('click', (event) => {
                const changeControl = event.target.closest('[data-bible-change]');
                const chapterButton = event.target.closest('[data-bible-read-chapter]');
                const verseButton = event.target.closest('[data-bible-read-verse]');
                const stopButton = event.target.closest('[data-bible-stop]');
                const shareButton = event.target.closest('[data-bible-share]');
                const offlineButton = event.target.closest('[data-offline-save]');

                if (! changeControl && ! chapterButton && ! verseButton && ! stopButton && ! shareButton && ! offlineButton) {
                    return;
                }

                if (changeControl) {
                    stopReading();
                    return;
                }

                if (offlineButton) {
                    const reader = readerFor(offlineButton);
                    const url = offlineButton.dataset.offlineSaveUrl || window.location.href;

                    if (! ('serviceWorker' in navigator) || ! window.caches) {
                        setShareStatus(reader, 'Offline saving is not available in this browser.');
                        return;
                    }

                    caches.open('mannarise-offline-reading-v1')
                        .then((cache) => cache.add(url))
                        .then(() => navigator.serviceWorker.controller?.postMessage?.({
                            type: 'CACHE_OFFLINE_URLS',
                            urls: [url, '/bible', '/daily', '/guided-prayer', '/devotionals', '/prayer-wall'],
                        }))
                        .then(() => setShareStatus(reader, 'Chapter saved for offline reading.'))
                        .catch(() => setShareStatus(reader, 'Unable to save this chapter offline.'));

                    return;
                }

                const button = chapterButton || verseButton || stopButton || shareButton;
                const reader = readerFor(button);

                if (! reader) {
                    return;
                }

                if (shareButton) {
                    const text = `${shareButton.dataset.bibleShareText || ''}\n${shareButton.dataset.bibleShareUrl || ''}`.trim();

                    if (navigator.share) {
                        navigator.share({
                            title: 'Bible verse',
                            text: shareButton.dataset.bibleShareText || '',
                            url: shareButton.dataset.bibleShareUrl || window.location.href,
                        }).then(() => setShareStatus(reader, 'Verse shared.')).catch(() => {});
                    } else if (navigator.clipboard) {
                        navigator.clipboard.writeText(text).then(() => setShareStatus(reader, 'Verse copied to clipboard.'));
                    } else {
                        setShareStatus(reader, 'Copy sharing is not available in this browser.');
                    }

                    return;
                }

                if (stopButton) {
                    stopReading(reader, 'Reading stopped.');
                    return;
                }

                const reference = button.dataset.bibleReference || 'Bible';
                const text = chapterButton
                    ? chapterText(reader, reference)
                    : `${reference}. ${button.dataset.bibleText || ''}`.trim();

                if (text === '' || text === `${reference}.`) {
                    setStatus(reader, 'No Bible text found to read.');
                    return;
                }

                speak(reader, text, chapterButton ? `Reading ${reference}.` : `Reading ${reference}.`);
            });

            document.addEventListener('livewire:navigating', () => stopReading());

            document.addEventListener('change', (event) => {
                if (event.target.closest('[data-bible-change]')) {
                    stopReading();
                }
            });
        })();
    </script>
</div>