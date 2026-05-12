@php
    $referenceTitle = trim(($book?->name ?? 'Bible').' '.$chapter);
    $chapterProgress = $chapterCount > 0 ? min(100, max(3, ((int) $chapter / $chapterCount) * 100)) : 0;
    $chapterMeta = $verses->count().' '.\Illuminate\Support\Str::plural('verse', $verses->count()).' · '.$languageLabel.' · '.$version;
@endphp

<div class="bible-reader-page" data-bible-reader data-bible-language="{{ $language }}">
    @if ($books->isEmpty())
        <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600">
            The Bible has not been imported yet. Run `php artisan db:seed --class=BibleSeeder`.
        </div>
    @else
        <section class="bible-reader-hero">
            <div class="min-w-0">
                <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900">
                    <x-ui.icon name="book-open" class="h-4 w-4" /> Bible reader
                </p>
                <h1>{{ $referenceTitle }}</h1>
                <p>{{ $chapterMeta }}</p>

                <div class="mt-4 max-w-xl">
                    <div class="flex items-center justify-between text-xs font-black uppercase tracking-normal text-slate-500">
                        <span>{{ $book?->name }}</span>
                        <span>{{ $chapter }} / {{ $chapterCount }}</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200">
                        <span class="block h-full rounded-full bg-emerald-700" style="width: {{ $chapterProgress }}%"></span>
                    </div>
                </div>

                <div class="reader-status-strip" aria-live="polite">
                    <span data-bible-audio-status></span>
                    <span data-bible-share-status></span>
                </div>
            </div>

            <div class="bible-hero-actions">
                @if ($lastReading)
                    <a href="{{ route('bible', ['book' => $lastReading->book?->slug, 'chapter' => $lastReading->chapter, 'language' => $lastReading->language, 'version' => $lastReading->version]) }}" class="btn-secondary">
                        <x-ui.icon name="bookmark" class="h-4 w-4" /> Continue
                    </a>
                @endif
                @auth
                    <button type="button" wire:click="markChapterRead" class="btn-primary">
                        <x-ui.icon name="check-circle" class="h-4 w-4" /> Mark read
                    </button>
                    <a href="{{ route('bible.notes') }}" class="btn-secondary">
                        <x-ui.icon name="journal" class="h-4 w-4" /> Notes
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">
                        <x-ui.icon name="log-in" class="h-4 w-4" /> Save notes
                    </a>
                @endauth
                <a href="{{ route('library.index') }}" class="btn-secondary">
                    <x-ui.icon name="library" class="h-4 w-4" /> Library
                </a>
            </div>
        </section>

        <section class="bible-mobile-reference lg:hidden">
            <button type="button" wire:click="previousChapter" data-bible-change aria-label="Previous chapter">
                <x-ui.icon name="chevron-left" class="h-5 w-5" />
            </button>
            <div>
                <span>{{ $referenceTitle }}</span>
                <small>{{ $chapterMeta }}</small>
            </div>
            <button type="button" wire:click="nextChapter" data-bible-change aria-label="Next chapter">
                <x-ui.icon name="chevron-right" class="h-5 w-5" />
            </button>
        </section>

        <section class="bible-reader-workspace">
            <aside class="bible-control-rail">
                <details class="bible-control-panel" open>
                    <summary>
                        <span>
                            <x-ui.icon name="settings" class="h-4 w-4" />
                            Change passage
                        </span>
                        <x-ui.icon name="chevron-right" class="h-4 w-4 bible-summary-icon" />
                    </summary>

                    <div class="bible-control-grid">
                        <label>
                            <span><x-ui.icon name="book-open" class="h-4 w-4" /> Book</span>
                            <select wire:model.live="bookSlug" data-bible-change class="field-input">
                                @foreach ($books as $option)
                                    <option value="{{ $option->slug }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>
                            <span><x-ui.icon name="bookmark" class="h-4 w-4" /> Chapter</span>
                            <select wire:model.live="chapter" data-bible-change class="field-input">
                                @if ($book)
                                    @for ($i = 1; $i <= $chapterCount; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @endif
                            </select>
                        </label>

                        <label>
                            <span><x-ui.icon name="globe" class="h-4 w-4" /> Language</span>
                            <select wire:model.live="language" data-bible-change class="field-input">
                                @foreach ($languages as $translation)
                                    <option value="{{ $translation['language'] }}">{{ $translation['language_label'] }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>
                            <span><x-ui.icon name="layers" class="h-4 w-4" /> Version</span>
                            <select wire:model.live="version" data-bible-change class="field-input">
                                @foreach ($versions as $translation)
                                    <option value="{{ $translation['version'] }}">{{ $translation['version'] }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="bible-search-control">
                            <span><x-ui.icon name="search" class="h-4 w-4" /> Search Bible</span>
                            <input type="search" wire:model.live.debounce.400ms="search" placeholder="Search words or phrase" class="field-input">
                        </label>
                    </div>
                </details>

                <div class="bible-action-panel">
                    <button type="button" data-bible-read-chapter data-bible-reference="{{ $referenceTitle }} {{ $version }}" class="btn-secondary" title="Listen" aria-label="Listen to this chapter">
                        <x-ui.icon name="volume-2" class="h-4 w-4" /> Listen
                    </button>
                    <button type="button" data-bible-stop class="btn-secondary" title="Stop" aria-label="Stop Bible audio">
                        <x-ui.icon name="square" class="h-4 w-4" /> Stop
                    </button>
                    <button type="button" data-offline-save data-offline-save-url="{{ request()->fullUrl() }}" class="btn-secondary" title="Save offline" aria-label="Save this chapter offline">
                        <x-ui.icon name="download" class="h-4 w-4" /> Save offline
                    </button>
                </div>

                <details class="bible-study-panel" open>
                    <summary>
                        <span>
                            <x-ui.icon name="sparkles" class="h-4 w-4" />
                            Study mode
                        </span>
                        <x-ui.icon name="chevron-right" class="h-4 w-4 bible-summary-icon" />
                    </summary>

                    <div class="bible-study-content">
                        <div>
                            <h2>Chapter summary</h2>
                            <p>{{ $studyGuide['summary'] }}</p>
                        </div>

                        <div>
                            <h2>Key themes</h2>
                            <div class="bible-theme-list">
                                @foreach ($studyGuide['themes'] as $theme)
                                    <span>{{ $theme }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h2>Reflection questions</h2>
                            <div class="space-y-2">
                                @foreach ($studyGuide['reflection_questions'] as $question)
                                    <p class="bible-study-note">{{ $question }}</p>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h2>Prayer points</h2>
                            <div class="space-y-2">
                                @foreach ($studyGuide['prayer_points'] as $point)
                                    <p class="bible-study-note">{{ $point }}</p>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h2>Related devotionals</h2>
                            <div class="space-y-2">
                                @forelse ($studyGuide['related_devotionals'] as $devotional)
                                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="bible-related-link">
                                        {{ $devotional->title }}
                                        <span>{{ $devotional->category?->name }}</span>
                                    </a>
                                @empty
                                    <p class="bible-study-note">No related devotional found yet. Published devotionals will appear here as your library grows.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </details>
            </aside>

            <main class="bible-reading-column">
                @if ($searchResults)
                    <section class="bible-search-results">
                        <div class="bible-search-results-header">
                            <div>
                                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900">
                                    <x-ui.icon name="search" class="h-4 w-4" /> Search results
                                </p>
                                <h2>{{ $searchResults->total() }} {{ \Illuminate\Support\Str::plural('verse', $searchResults->total()) }} found</h2>
                            </div>
                            <span>{{ strtoupper($language) }} {{ $version }}</span>
                        </div>

                        <div class="bible-result-list">
                            @forelse ($searchResults as $result)
                                <article>
                                    <a href="{{ route('bible', ['book' => $result->book->slug, 'chapter' => $result->chapter, 'language' => $language, 'version' => $version]).'#verse-'.$result->verse }}">
                                        {{ $result->book->name }} {{ $result->chapter }}:{{ $result->verse }}
                                    </a>
                                    <p>{{ $result->text }}</p>
                                </article>
                            @empty
                                <p class="bible-empty-message">No verses matched that search.</p>
                            @endforelse
                        </div>

                        <div class="mt-5">{{ $searchResults->links() }}</div>
                    </section>
                @endif

                <article class="bible-reading-surface">
                    <header class="bible-reading-header">
                        <div>
                            <span>{{ $book?->testament }} · {{ strtoupper($language) }} {{ $version }}</span>
                            <h2>{{ $referenceTitle }}</h2>
                        </div>
                        <div class="bible-reading-actions">
                            <button type="button" wire:click="previousChapter" data-bible-change class="btn-secondary">
                                <x-ui.icon name="chevron-left" class="h-4 w-4" /> Previous
                            </button>
                            <button type="button" wire:click="nextChapter" data-bible-change class="btn-primary">
                                Next <x-ui.icon name="chevron-right" class="h-4 w-4" />
                            </button>
                        </div>
                    </header>

                    @if ($verses->isEmpty())
                        <p class="bible-empty-message">No verses are available for this chapter in the selected translation.</p>
                    @else
                        <div class="bible-verses reading-copy">
                            @foreach ($verses as $verse)
                                @php
                                    $engagement = $engagements[$verse->id] ?? null;
                                    $highlight = $engagement?->highlight_color;
                                    $highlightClass = match ($highlight) {
                                        'emerald' => 'is-highlighted-emerald',
                                        'sky' => 'is-highlighted-sky',
                                        'rose' => 'is-highlighted-rose',
                                        'violet' => 'is-highlighted-violet',
                                        'amber' => 'is-highlighted-amber',
                                        default => '',
                                    };
                                    $reference = "{$book?->name} {$verse->chapter}:{$verse->verse} {$version}";
                                    $hasPersonalMark = $engagement?->bookmarked_at || $engagement?->note || $highlight;
                                @endphp

                                <div id="verse-{{ $verse->verse }}" class="bible-verse-row {{ $highlightClass }}">
                                    <p data-bible-verse-text>
                                        <sup>{{ $verse->verse }}</sup>{{ $verse->text }}
                                    </p>

                                    <details class="bible-verse-tools" data-bible-verse-tools>
                                        <summary aria-label="Open tools for {{ $reference }}">
                                            <x-ui.icon name="{{ $hasPersonalMark ? 'bookmark' : 'more-horizontal' }}" class="h-4 w-4" />
                                        </summary>

                                        <div class="bible-verse-tool-panel">
                                            <div class="bible-verse-tool-header">
                                                <div>
                                                    <span>Verse tools</span>
                                                    <strong>{{ $reference }}</strong>
                                                </div>
                                                <small>{{ $hasPersonalMark ? 'Saved to your rhythm' : 'Listen, save, highlight, or share' }}</small>
                                            </div>

                                            <div class="bible-tool-actions">
                                                <button
                                                    type="button"
                                                    data-bible-read-verse
                                                    data-bible-reference="{{ $reference }}"
                                                    data-bible-text="{{ $verse->text }}"
                                                    class="btn-secondary"
                                                    aria-label="Listen to {{ $book?->name }} {{ $verse->chapter }}:{{ $verse->verse }}"
                                                >
                                                    <x-ui.icon name="volume-2" class="h-4 w-4" /> Listen
                                                </button>

                                                @auth
                                                    <button type="button" wire:click="toggleBookmark({{ $verse->id }})" class="btn-secondary {{ $engagement?->bookmarked_at ? 'is-active' : '' }}">
                                                        <x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $engagement?->bookmarked_at ? 'Saved' : 'Save' }}
                                                    </button>
                                                @else
                                                    <a href="{{ route('login') }}" class="btn-secondary">
                                                        <x-ui.icon name="log-in" class="h-4 w-4" /> Log in
                                                    </a>
                                                @endauth

                                                <button
                                                    type="button"
                                                    @auth wire:click="recordShare({{ $verse->id }})" @endauth
                                                    data-bible-share
                                                    data-bible-share-text="{{ $reference }} - {{ $verse->text }}"
                                                    data-bible-share-url="{{ route('bible', ['book' => $book?->slug, 'chapter' => $verse->chapter, 'language' => $language, 'version' => $version]).'#verse-'.$verse->verse }}"
                                                    class="btn-secondary"
                                                >
                                                    <x-ui.icon name="share-2" class="h-4 w-4" /> Share
                                                </button>
                                            </div>

                                            @auth
                                                <div class="bible-highlight-picker">
                                                    <span>Highlight</span>
                                                    <div>
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
                                                                class="{{ $swatchClass }} {{ $highlight === $color ? 'is-selected' : '' }}"
                                                                aria-label="Highlight {{ $reference }} in {{ $color }}"
                                                            ></button>
                                                        @endforeach
                                                        @if ($highlight)
                                                            <button type="button" wire:click="setHighlight({{ $verse->id }}, null)" class="clear-highlight">Clear</button>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="bible-note-editor">
                                                    <label for="note-{{ $verse->id }}">Personal note</label>
                                                    <textarea id="note-{{ $verse->id }}" wire:model="notes.{{ $verse->id }}" rows="3" placeholder="What is God showing you in this verse?" class="field-input"></textarea>
                                                    <button type="button" wire:click="saveNote({{ $verse->id }})" class="btn-primary">
                                                        <x-ui.icon name="send" class="h-4 w-4" /> Save note
                                                    </button>
                                                </div>
                                            @endauth
                                        </div>
                                    </details>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </article>
            </main>
        </section>

    @endif

    <script>
        (() => {
            if (window.MannaRiseBibleAudioReady) {
                return;
            }

            window.MannaRiseBibleAudioReady = true;

            function collapseMobileReaderPanels() {
                if (! window.matchMedia?.('(max-width: 1023px)').matches) {
                    return;
                }

                document.querySelectorAll('.bible-control-panel[open], .bible-study-panel[open]').forEach((panel) => {
                    panel.open = false;
                });
            }

            collapseMobileReaderPanels();

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
                const openVerseTool = document.querySelector('[data-bible-verse-tools][open]');

                if (openVerseTool && ! event.target.closest('[data-bible-verse-tools]')) {
                    openVerseTool.open = false;
                }

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

            document.addEventListener('toggle', (event) => {
                if (! event.target.matches?.('[data-bible-verse-tools][open]')) {
                    return;
                }

                document.querySelectorAll('[data-bible-verse-tools][open]').forEach((tool) => {
                    if (tool !== event.target) {
                        tool.open = false;
                    }
                });
            }, true);

            document.addEventListener('livewire:navigating', () => stopReading());

            document.addEventListener('change', (event) => {
                if (event.target.closest('[data-bible-change]')) {
                    stopReading();
                }
            });
        })();
    </script>
</div>
