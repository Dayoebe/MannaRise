<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-indigo-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,26rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-indigo-200 bg-indigo-50 text-indigo-900"><x-ui.icon name="award" class="h-4 w-4" /> Memory verse</p>
                <h1 class="mt-3 app-section-title">Weekly memory verse challenge</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Practice this week&apos;s verse, hide words as you grow confident, enable reminders, and earn completion badges.</p>
            </div>
            <div class="app-surface grid grid-cols-3 gap-3 border-indigo-200 bg-indigo-50 p-4 text-center">
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">{{ $progress?->practiced_count ?? 0 }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Practices</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">{{ $completedWeeks }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Badges</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">{{ $progress?->completed_at ? 'Yes' : 'No' }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Complete</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)] lg:items-start">
        <div class="space-y-5">
            <article class="app-panel border-indigo-200 bg-indigo-50">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="app-eyebrow border-indigo-200 bg-white text-indigo-900"><x-ui.icon name="book-open" class="h-4 w-4" /> This week</p>
                    <span class="rounded-full bg-white px-3 py-1 text-sm font-black text-indigo-900 shadow-sm">Week of {{ \Illuminate\Support\Carbon::parse($challenge['week_start'])->format('M j') }}</span>
                </div>
                <blockquote class="mt-5 font-serif text-2xl font-semibold leading-9 text-slate-950">"{{ $challenge['text'] }}"</blockquote>
                <p class="mt-4 text-sm font-black text-indigo-900">{{ $challenge['reference'] }}</p>
                @if ($challenge['book_slug'])
                    <a href="{{ route('bible', ['book' => $challenge['book_slug'], 'chapter' => $challenge['chapter']]) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-indigo-900 hover:text-indigo-950">
                        Open chapter <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </a>
                @endif
            </article>

            <article data-memory-practice class="app-panel border-sky-200 bg-white">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="eye-off" class="h-4 w-4" /> Hidden-word practice</p>
                        <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Practice recall</h2>
                    </div>
                    <select data-memory-level class="field-input border-sky-300 focus:border-sky-600 focus:ring-sky-100 sm:max-w-56">
                        <option value="3">Hide every third word</option>
                        <option value="2">Hide every other word</option>
                        <option value="1">First-letter mode</option>
                    </select>
                </div>

                <p data-memory-output class="mt-5 min-h-36 rounded-xl border border-sky-100 bg-sky-50 p-4 font-serif text-xl font-semibold leading-9 text-slate-950"></p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button" data-memory-reveal class="btn-secondary border-sky-200"><x-ui.icon name="eye" class="h-4 w-4" /> Reveal</button>
                    <button type="button" data-memory-hide class="btn-secondary border-sky-200"><x-ui.icon name="eye-off" class="h-4 w-4" /> Hide words</button>
                    @auth
                        <button type="button" wire:click="logPractice" class="btn-primary bg-sky-700 hover:bg-sky-800"><x-ui.icon name="check-circle" class="h-4 w-4" /> Log practice</button>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary bg-sky-700 hover:bg-sky-800"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to track</a>
                    @endauth
                </div>
            </article>
        </div>

        <aside class="space-y-4 lg:sticky lg:top-36">
            <div class="app-panel border-emerald-200 bg-emerald-50">
                <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="bell" class="h-4 w-4 text-emerald-900" /> Reminder</h2>
                <p class="mt-2 text-sm leading-6 text-slate-700">Turn on an in-app reminder marker for this week&apos;s verse.</p>
                @auth
                    <button type="button" wire:click="toggleReminder" class="mt-4 btn-secondary w-full border-emerald-200 bg-white">
                        <x-ui.icon name="bell" class="h-4 w-4" /> {{ $progress?->reminder_enabled ? 'Disable reminder' : 'Enable reminder' }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="mt-4 btn-primary w-full"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in for reminders</a>
                @endauth
            </div>

            <div class="app-panel border-amber-200 bg-amber-50">
                <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="award" class="h-4 w-4 text-amber-900" /> Completion badges</h2>
                <div class="mt-4 space-y-2">
                    @foreach ($badges as $badge)
                        <div class="flex items-center justify-between gap-3 rounded-xl border bg-white px-3 py-2 text-sm font-bold {{ $badge['earned'] ? 'border-amber-200 text-amber-950' : 'border-slate-200 text-slate-500' }}">
                            <span>{{ $badge['label'] }}</span>
                            <x-ui.icon name="{{ $badge['earned'] ? 'check-circle' : 'lock' }}" class="h-4 w-4" />
                        </div>
                    @endforeach
                </div>
                @auth
                    <button type="button" wire:click="complete" class="mt-4 btn-primary w-full bg-amber-600 text-slate-950 hover:bg-amber-500">
                        <x-ui.icon name="award" class="h-4 w-4" /> {{ $progress?->completed_at ? 'Completed this week' : 'Mark memorized' }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="mt-4 btn-secondary w-full border-amber-200 bg-white">Log in to earn badges</a>
                @endauth
            </div>

            <div class="app-panel border-slate-200">
                <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="clock" class="h-4 w-4 text-slate-700" /> Recent completions</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($recentCompletions as $completion)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <p class="text-sm font-black text-slate-950">{{ $completion->reference }}</p>
                            <p class="mt-1 text-xs font-bold text-slate-500">{{ $completion->completed_at->format('M j, Y') }}</p>
                        </div>
                    @empty
                        <p class="text-sm leading-6 text-slate-600">Completed verses will appear here.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>

    <script>
        (() => {
            const root = document.querySelector('[data-memory-practice]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const verse = @js($challenge['text']);
            const output = root.querySelector('[data-memory-output]');
            const level = root.querySelector('[data-memory-level]');
            const reveal = root.querySelector('[data-memory-reveal]');
            const hide = root.querySelector('[data-memory-hide]');

            function words() {
                return verse.split(/\s+/).filter(Boolean);
            }

            function renderHidden() {
                const mode = Number(level.value);

                output.textContent = words().map((word, index) => {
                    if (mode === 1) {
                        return `${word[0]}${'_'.repeat(Math.max(2, word.replace(/[^A-Za-z]/g, '').length - 1))}`;
                    }

                    return (index + 1) % mode === 0 ? '______' : word;
                }).join(' ');
            }

            level.addEventListener('change', renderHidden);
            reveal.addEventListener('click', () => {
                output.textContent = verse;
            });
            hide.addEventListener('click', renderHidden);
            renderHidden();
        })();
    </script>
</div>
