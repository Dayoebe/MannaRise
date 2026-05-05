<div class="space-y-6 sm:space-y-8">
    @php
        $dailyVerse = $dailyRhythm['verse'];
        $dailyAffirmation = $dailyRhythm['affirmation'];
        $dailyChallenge = $dailyRhythm['challenge'];
        $dashboardChallenge = $catchUpPlan ?: $dailyChallenge;
        $firstChallengeReading = $dashboardChallenge ? $dashboardChallenge['readings']->first() : null;
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
                        <a href="{{ route('devotionals.show', $todayDevotional->slug) }}" class="btn-primary w-full"><x-ui.icon name="sparkles" class="h-4 w-4" /> Continue today's reading</a>
                    @else
                        <a href="{{ route('devotionals.index') }}" class="btn-primary w-full"><x-ui.icon name="sparkles" class="h-4 w-4" /> Find a devotional</a>
                    @endif
                    <a href="{{ route('journal.index') }}" class="btn-secondary w-full"><x-ui.icon name="journal" class="h-4 w-4" /> Write reflection</a>
                </div>
            </div>
        </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
        @foreach ([
            ['book-open', 'Bible', $unifiedProgress['stats']['bible_chapters'].' chapters', 'border-blue-200 bg-blue-50 text-blue-900'],
            ['heart', 'Prayer', $unifiedProgress['stats']['prayer_days'].' days', 'border-rose-200 bg-rose-50 text-rose-900'],
            ['journal', 'Journal', $unifiedProgress['stats']['journal_entries'], 'border-sky-200 bg-sky-50 text-sky-900'],
            ['route', 'Plans', $unifiedProgress['stats']['plan_days'].' days', 'border-emerald-200 bg-emerald-50 text-emerald-900'],
            ['award', 'Memory', $unifiedProgress['stats']['memory_completed'], 'border-amber-200 bg-amber-50 text-amber-900'],
            ['star', 'Testimonies', $unifiedProgress['stats']['testimonies'], 'border-violet-200 bg-violet-50 text-violet-900'],
        ] as [$icon, $label, $value, $classes])
            <div class="metric-card {{ $classes }}">
                <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon :name="$icon" class="h-4 w-4" /> {{ $label }}</p>
                <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <section class="app-panel border-cyan-200 bg-cyan-50">
        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,28rem)] lg:items-start">
            <div>
                <p class="app-eyebrow border-cyan-200 bg-white text-cyan-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> Unified progress</p>
                <h2 class="mt-3 app-section-title">Your spiritual rhythm</h2>
                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $unifiedProgress['encouragement'] }}</p>
                <p class="mt-3 rounded-xl border border-white bg-white p-3 text-sm font-semibold leading-6 text-slate-700">{{ $unifiedProgress['journal_pattern'] }}</p>
                @if ($unifiedProgress['latest_reading'])
                    <a href="{{ route('bible', ['book' => $unifiedProgress['latest_reading']->book?->slug, 'chapter' => $unifiedProgress['latest_reading']->chapter, 'language' => $unifiedProgress['latest_reading']->language, 'version' => $unifiedProgress['latest_reading']->version]) }}" class="mt-4 btn-primary bg-cyan-700 hover:bg-cyan-800">
                        Continue {{ $unifiedProgress['latest_reading']->book?->name }} {{ $unifiedProgress['latest_reading']->chapter }}
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </a>
                @endif
            </div>

            <div class="app-surface border-cyan-200 bg-white p-4">
                <h3 class="font-black tracking-normal text-slate-950">Gentle streaks</h3>
                <div class="mt-4 grid gap-3">
                    @foreach ([
                        ['Bible reading', $unifiedProgress['stats']['bible_streak'], 'bg-blue-600'],
                        ['Prayer', $unifiedProgress['stats']['prayer_streak'], 'bg-rose-600'],
                        ['Journal', $unifiedProgress['stats']['journal_streak'], 'bg-sky-600'],
                    ] as [$label, $days, $bar])
                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm font-bold text-slate-700">
                                <span>{{ $label }}</span>
                                <span>{{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}</span>
                            </div>
                            <div class="mt-1 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $bar }}" style="width: {{ min(100, $days * 14) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="mt-5 font-black tracking-normal text-slate-950">Plan progress</h3>
                <div class="mt-3 space-y-3">
                    @foreach ($unifiedProgress['plans'] as $plan)
                        <a href="{{ route('devotional-plans.show', $plan['slug']) }}" class="block rounded-xl border border-slate-100 bg-slate-50 p-3 hover:border-cyan-200 hover:bg-cyan-50">
                            <div class="flex items-center justify-between gap-3 text-sm font-bold text-slate-700">
                                <span>{{ $plan['title'] }}</span>
                                <span>{{ $plan['completed'] }}/{{ $plan['duration'] }}</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-white">
                                <div class="h-full rounded-full bg-emerald-700" style="width: {{ $plan['percent'] }}%"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="app-panel border-amber-200 bg-amber-50">
        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,24rem)] lg:items-start">
            <div>
                <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="bell" class="h-4 w-4" /> Reminders and digest</p>
                <h2 class="mt-3 app-section-title">Your weekly spiritual recap</h2>
                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $retentionSummary['weekly_digest']['sentence'] }}</p>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-white bg-white p-3">
                        <p class="text-xs font-bold uppercase tracking-normal text-amber-900">Prayer</p>
                        <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $retentionSummary['weekly_digest']['prayer_days'] }} days</p>
                    </div>
                    <div class="rounded-xl border border-white bg-white p-3">
                        <p class="text-xs font-bold uppercase tracking-normal text-amber-900">Readings</p>
                        <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $retentionSummary['weekly_digest']['devotional_days'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white bg-white p-3">
                        <p class="text-xs font-bold uppercase tracking-normal text-amber-900">Consistency</p>
                        <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $retentionSummary['weekly_digest']['consistent_days'] }} days</p>
                    </div>
                </div>
            </div>

            <div class="app-surface border-amber-200 bg-white p-4">
                <h3 class="font-black tracking-normal text-slate-950">Next reminder</h3>
                @if ($retentionSummary['next_reminder_at'])
                    <p class="mt-2 text-2xl font-black tracking-normal text-slate-950">{{ $retentionSummary['next_reminder_at']->format('D, M j') }}</p>
                    <p class="mt-1 text-sm font-bold text-amber-900">{{ $retentionSummary['next_reminder_at']->format('g:i A T') }}</p>
                @else
                    <p class="mt-2 text-sm leading-6 text-slate-600">No active reminder is scheduled yet.</p>
                @endif
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('growth-path.index') }}" class="btn-primary bg-amber-600 text-slate-950 hover:bg-amber-500"><x-ui.icon name="route" class="h-4 w-4" /> Today&apos;s path</a>
                    <a href="{{ route('reminders.settings') }}" class="btn-secondary border-amber-200"><x-ui.icon name="settings" class="h-4 w-4" /> Reminder settings</a>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
        <article class="app-panel border-violet-200 bg-violet-50">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="app-eyebrow border-violet-200 bg-white text-violet-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> Spiritual growth score</p>
                    <h2 class="mt-3 text-5xl font-black tracking-normal text-slate-950">{{ $growthScore['score'] }}</h2>
                    <p class="mt-1 text-sm font-black text-violet-900">{{ $growthScore['label'] }} · 7-day view</p>
                </div>
                <span class="rounded-full bg-white px-3 py-1 text-sm font-black text-violet-900 shadow-sm">
                    {{ $growthScore['trend'] >= 0 ? '+' : '' }}{{ $growthScore['trend'] }} vs 30 days
                </span>
            </div>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach ($growthScore['breakdown'] as $label => $value)
                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm font-bold text-slate-700">
                            <span>{{ $label }}</span>
                            <span>{{ $value }}%</span>
                        </div>
                        <div class="mt-1 h-2 overflow-hidden rounded-full bg-white">
                            <div class="h-full rounded-full bg-violet-700" style="width: {{ $value }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="app-panel border-emerald-200">
            @php
                $pathDefinition = $personalPath['definition'];
                $pathDevotional = $personalPath['devotional'];
            @endphp
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="route" class="h-4 w-4" /> Personalized daily path</p>
                    <h2 class="mt-3 app-section-title">{{ $pathDefinition['label'] }}</h2>
                </div>
                <a href="{{ route('growth-path.index') }}" class="btn-secondary w-full sm:w-auto">Tune path <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
            </div>
            <div class="mt-5 grid gap-3 md:grid-cols-2">
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-sm font-black text-emerald-900">Scripture</p>
                    <p class="mt-1 font-black tracking-normal text-slate-950">{{ $pathDefinition['reference'] }}</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $pathDefinition['affirmation'] }}</p>
                </div>
                <div class="rounded-xl border border-sky-100 bg-sky-50 p-4">
                    <p class="text-sm font-black text-sky-900">Journal prompt</p>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $pathDefinition['journal_prompt'] }}</p>
                </div>
            </div>
            @if ($pathDevotional)
                <a href="{{ route('devotionals.show', $pathDevotional->slug) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-emerald-900 hover:text-emerald-950">
                    Recommended: {{ $pathDevotional->title }} <x-ui.icon name="chevron-right" class="h-4 w-4" />
                </a>
            @endif
        </article>
    </section>

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
                <p class="mt-4 font-black tracking-normal text-slate-950">
                    @if ($catchUpPlan && $catchUpPlan['is_catch_up'])
                        Catch-up: {{ $catchUpPlan['reading_label'] }}
                    @else
                        Day {{ $dailyChallenge['day'] }}: {{ $dailyChallenge['reading_label'] }}
                    @endif
                </p>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white">
                    <div class="h-full rounded-full bg-emerald-700" style="width: {{ min(100, $catchUpPlan['progress_percent'] ?? $dailyChallenge['progress_percent']) }}%"></div>
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

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('bible.notes') }}" class="dashboard-action-card">
            <span class="icon-badge text-blue-800"><x-ui.icon name="bookmark" class="h-5 w-5" /></span>
            <span>
                <span class="block font-black tracking-normal text-slate-950">Bible notes</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Review verses you saved, highlighted, or wrote notes on.</span>
            </span>
        </a>
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
        <a href="{{ route('offline.library') }}" class="dashboard-action-card">
            <span class="icon-badge text-amber-800"><x-ui.icon name="download" class="h-5 w-5" /></span>
            <span>
                <span class="block font-black tracking-normal text-slate-950">Offline library</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Keep core Bible, prayer, and devotional pages close on mobile.</span>
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
