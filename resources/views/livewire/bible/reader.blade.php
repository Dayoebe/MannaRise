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
                <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> King James Version</p>
                <h1 class="mt-3 app-section-title">Bible reader</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Read the full public-domain KJV Bible by book and chapter, or search across all verses.</p>
            </div>
            <a href="{{ route('library.index') }}" class="btn-secondary w-full border-cyan-200 text-cyan-900 hover:bg-cyan-50 sm:w-auto"><x-ui.icon name="library" class="h-4 w-4" /> Open library</a>
        </div>
    </div>

    @if ($books->isEmpty())
        <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600">
            The Bible has not been imported yet. Run `php artisan db:seed --class=BibleSeeder`.
        </div>
    @else
        <section class="app-panel border-sky-200 bg-sky-50">
            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_8rem_minmax(0,1fr)]">
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
                            @for ($i = 1; $i <= $book->chapters; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        @endif
                    </select>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-blue-700" /> Search Bible</label>
                    <input type="search" wire:model.live.debounce.400ms="search" placeholder="Search words or phrase" class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                </div>
            </div>
        </section>

        @if ($searchResults)
            <section class="app-panel border-mauve-200 bg-mauve-50">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="search" class="h-5 w-5 text-mauve-700" /> Search results</h2>
                    <span class="inline-flex w-fit rounded-full bg-white px-3 py-1 text-sm font-bold text-mauve-800 shadow-sm">{{ $searchResults->total() }} verses</span>
                </div>

                <div class="space-y-3">
                    @forelse ($searchResults as $result)
                        <article class="rounded-xl border border-mauve-200 bg-white p-4 shadow-sm">
                            <p class="text-sm font-black tracking-normal text-mauve-800">{{ $result->book->name }} {{ $result->chapter }}:{{ $result->verse }}</p>
                            <p class="mt-2 text-base leading-7 text-slate-800">{{ $result->text }}</p>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-mauve-300 bg-white p-4 text-sm text-slate-600">No verses matched that search.</p>
                    @endforelse
                </div>

                <div class="mt-5">{{ $searchResults->links() }}</div>
            </section>
        @endif

        <article class="app-panel border-olive-200 bg-white p-5 sm:p-8" data-bible-reader>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full border border-olive-200 bg-olive-50 px-3 py-1 text-sm font-black uppercase tracking-normal text-olive-800"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $book?->testament }}</p>
                    <h2 class="mt-3 break-words text-3xl font-black tracking-normal text-slate-950 sm:text-4xl">{{ $book?->name }} {{ $chapter }}</h2>
                    <p data-bible-audio-status class="mt-2 min-h-5 text-sm font-bold text-olive-800"></p>
                </div>
                <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:justify-end">
                    <button type="button" data-bible-read-chapter data-bible-reference="{{ $book?->name }} {{ $chapter }}" class="btn-secondary col-span-2 border-olive-300 bg-olive-50 px-3 text-olive-900 hover:bg-olive-100 sm:col-span-1">
                        <x-ui.icon name="volume-2" class="h-4 w-4" /> Listen
                    </button>
                    <button type="button" data-bible-stop class="btn-secondary border-rose-200 px-3 text-rose-900 hover:bg-rose-50">
                        <x-ui.icon name="square" class="h-4 w-4" /> Stop
                    </button>
                    <button type="button" wire:click="previousChapter" data-bible-change class="btn-secondary border-slate-300 px-3">
                        <x-ui.icon name="chevron-left" class="h-4 w-4" /> Previous
                    </button>
                    <button type="button" wire:click="nextChapter" data-bible-change class="btn-primary px-3">
                        Next <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div class="reading-copy mt-6 space-y-4">
                @foreach ($verses as $verse)
                    <div class="group flex gap-3 rounded-xl border border-transparent p-2 transition hover:border-amber-100 hover:bg-amber-50/50">
                        <button
                            type="button"
                            data-bible-read-verse
                            data-bible-reference="{{ $book?->name }} {{ $verse->chapter }}:{{ $verse->verse }}"
                            data-bible-text="{{ $verse->text }}"
                            class="mt-1 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-amber-200 bg-amber-50 text-amber-900 shadow-sm transition hover:border-amber-400 hover:bg-amber-100"
                            aria-label="Listen to {{ $book?->name }} {{ $verse->chapter }}:{{ $verse->verse }}"
                        >
                            <x-ui.icon name="volume-2" class="h-4 w-4" />
                        </button>
                        <p data-bible-verse-text class="min-w-0 flex-1">
                            <sup class="mr-2 inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-amber-100 px-1 text-xs font-black text-amber-900">{{ $verse->verse }}</sup>{{ $verse->text }}
                        </p>
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

            function setStatus(reader, message) {
                const status = statusFor(reader);

                if (status) {
                    status.textContent = message;
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

            function speechVoice() {
                const voices = window.speechSynthesis?.getVoices?.() || [];

                return voices.find((voice) => voice.lang?.toLowerCase().startsWith('en'))
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
                const voice = speechVoice();

                if (voice) {
                    utterance.voice = voice;
                    utterance.lang = voice.lang;
                } else {
                    utterance.lang = 'en-US';
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

                if (! changeControl && ! chapterButton && ! verseButton && ! stopButton) {
                    return;
                }

                if (changeControl) {
                    stopReading();
                    return;
                }

                const button = chapterButton || verseButton || stopButton;
                const reader = readerFor(button);

                if (! reader) {
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
