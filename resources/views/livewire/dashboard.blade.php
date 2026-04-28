<div class="space-y-6 sm:space-y-8">
    @php
        $dailyVerse = $dailyRhythm['verse'];
        $dailyAffirmation = $dailyRhythm['affirmation'];
        $dailyChallenge = $dailyRhythm['challenge'];
        $firstChallengeReading = $dailyChallenge ? $dailyChallenge['readings']->first() : null;
    @endphp

    <div wire:loading.flex class="loading-hint items-center gap-2">
        <x-ui.icon name="sparkles" class="h-4 w-4 animate-pulse" /> Updating your dashboard...
    </div>

    <div class="page-hero border-emerald-200">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-cyan-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-blue-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,26rem)] lg:items-end">
            <div>
                <p class="app-eyebrow"><x-ui.icon name="layout-dashboard" class="h-4 w-4" /> Personal growth dashboard</p>
                <h1 class="mt-3 app-section-title">Welcome back, {{ auth()->user()->name }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Your devotionals, saved readings, journal reflections, prayer life, and consistency rhythm are gathered here.</p>
            </div>

            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                @if ($todayDevotional)
                    <a href="{{ route('devotionals.show', $todayDevotional->slug) }}" class="btn-primary w-full"><x-ui.icon name="sparkles" class="h-4 w-4" /> Continue today’s reading</a>
                @else
                    <a href="{{ route('devotionals.index') }}" class="btn-primary w-full"><x-ui.icon name="sparkles" class="h-4 w-4" /> Find a devotional</a>
                @endif
                <a href="{{ route('journal.index') }}" class="btn-secondary w-full"><x-ui.icon name="journal" class="h-4 w-4" /> Write reflection</a>
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([['bookmark', 'Favorites', $stats['favorites'], 'border-emerald-200 bg-emerald-50 text-emerald-900'], ['journal', 'Journal', $stats['journal_entries'], 'border-sky-200 bg-sky-50 text-sky-900'], ['heart', 'Prayers', $stats['prayer_requests'], 'border-rose-200 bg-rose-50 text-rose-900'], ['sparkles', 'Completed', $stats['completed'], 'border-lime-200 bg-lime-50 text-lime-900'], ['star', 'Streak', $stats['streak'].' days', 'border-orange-200 bg-orange-50 text-orange-900']] as [$icon, $label, $value, $classes])
            <div class="metric-card {{ $classes }}">
                <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon :name="$icon" class="h-4 w-4" /> {{ $label }}</p>
                <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <section class="grid gap-4 lg:grid-cols-3">
        <article class="app-panel border-blue-200 bg-blue-50">
            <p class="app-eyebrow border-blue-200 bg-white text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Verse of the day</p>
            @if ($dailyVerse)
                <blockquote class="mt-4 font-serif text-lg font-semibold leading-7 text-slate-950">"{{ $dailyVerse->text }}"</blockquote>
                <p class="mt-3 text-sm font-black text-blue-900">{{ $dailyVerse->book->name }} {{ $dailyVerse->chapter }}:{{ $dailyVerse->verse }} KJV</p>
            @else
                <p class="mt-4 text-sm leading-6 text-slate-600">Import Bible seed data to show today&apos;s verse.</p>
            @endif
        </article>

        <article class="app-panel border-amber-200 bg-amber-50">
            <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Daily affirmation</p>
            <p class="mt-4 font-serif text-lg font-semibold leading-7 text-slate-950">{{ $dailyAffirmation['text'] }}</p>
            <p class="mt-3 text-sm font-black text-amber-900">{{ $dailyAffirmation['reference'] }}</p>
        </article>

        <article class="app-panel border-emerald-200 bg-emerald-50">
            <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="star" class="h-4 w-4" /> Bible-in-a-year</p>
            @if ($dailyChallenge)
                <p class="mt-4 font-black tracking-normal text-slate-950">Day {{ $dailyChallenge['day'] }}: {{ $dailyChallenge['reading_label'] }}</p>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white">
                    <div class="h-full rounded-full bg-emerald-700" style="width: {{ min(100, $dailyChallenge['progress_percent']) }}%"></div>
                </div>
                <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('daily.index') }}" class="btn-secondary border-emerald-200 px-3">Daily page</a>
                    @if ($firstChallengeReading)
                        <a href="{{ route('bible', ['book' => $firstChallengeReading['slug'], 'chapter' => $firstChallengeReading['chapter']]) }}" class="btn-primary px-3">Start</a>
                    @endif
                </div>
            @else
                <p class="mt-4 text-sm leading-6 text-slate-600">Import Bible seed data to generate the challenge.</p>
            @endif
        </article>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <a href="{{ route('devotionals.index') }}" class="dashboard-action-card">
            <span class="icon-badge text-emerald-800"><x-ui.icon name="sparkles" class="h-5 w-5" /></span>
            <span>
                <span class="block font-black tracking-normal text-slate-950">Read devotionals</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Search by topic and continue your spiritual growth rhythm.</span>
            </span>
        </a>
        <a href="{{ route('prayer-requests.submit') }}" class="dashboard-action-card">
            <span class="icon-badge text-rose-800"><x-ui.icon name="heart" class="h-5 w-5" /></span>
            <span>
                <span class="block font-black tracking-normal text-slate-950">Submit prayer</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Share a request privately or with the prayer wall.</span>
            </span>
        </a>
        <a href="{{ route('favorites.index') }}" class="dashboard-action-card">
            <span class="icon-badge text-amber-800"><x-ui.icon name="bookmark" class="h-5 w-5" /></span>
            <span>
                <span class="block font-black tracking-normal text-slate-950">Saved readings</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Return to the devotionals that blessed you most.</span>
            </span>
        </a>
    </section>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <section class="app-panel border-sky-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="journal" class="h-5 w-5 text-sky-800" /> Recent journal entries</h2>
                    <p class="mt-1 text-sm text-slate-600">Your latest reflections and lessons.</p>
                </div>
                <a href="{{ route('journal.index') }}" class="btn-secondary w-full border-sky-200 sm:w-auto">Open journal</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentJournalEntries as $entry)
                    <article class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                        <h3 class="font-black tracking-normal text-slate-950">{{ $entry->title }}</h3>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $entry->entry_date->format('M j, Y') }} @if ($entry->devotional) · {{ $entry->devotional->title }} @endif</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($entry->content, 135) }}</p>
                    </article>
                @empty
                    <x-ui.empty-state title="No journal entries yet" message="After reading a devotional, write what you learned, what you prayed, and what you want to act on." action-label="Start journaling" :action-href="route('journal.index')" />
                @endforelse
            </div>
        </section>

        <section class="app-panel border-emerald-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="bookmark" class="h-5 w-5 text-emerald-800" /> Saved devotionals</h2>
                    <p class="mt-1 text-sm text-slate-600">Quick access to your favorite readings.</p>
                </div>
                <a href="{{ route('favorites.index') }}" class="btn-secondary w-full border-emerald-200 sm:w-auto">View favorites</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentFavorites as $devotional)
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="block rounded-2xl border border-emerald-100 bg-emerald-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-100">
                        <span class="block font-black tracking-normal text-slate-950">{{ $devotional->title }}</span>
                        <span class="mt-1 block text-sm font-bold text-emerald-800">{{ $devotional->category?->name }}</span>
                    </a>
                @empty
                    <x-ui.empty-state title="No saved devotionals" message="Save devotionals you want to revisit during prayer, study, or difficult seasons." action-label="Browse devotionals" :action-href="route('devotionals.index')" />
                @endforelse
            </div>
        </section>
    </div>
</div>
