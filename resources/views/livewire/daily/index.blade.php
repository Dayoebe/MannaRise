<div class="space-y-6 sm:space-y-8">
    @php
        $verse = $dailyRhythm['verse'];
        $affirmation = $dailyRhythm['affirmation'];
        $challenge = $dailyRhythm['challenge'];
        $firstReading = $challenge ? $challenge['readings']->first() : null;
    @endphp

    <section class="page-hero border-emerald-200">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-400"></span>
        </div>

        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow"><x-ui.icon name="star" class="h-4 w-4" /> Daily rhythm</p>
                <h1 class="mt-3 app-section-title">Verse, affirmation, and Bible challenge</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    A focused daily rhythm for scripture, confession, and steady Bible reading.
                </p>
            </div>

            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                <a href="{{ route('bible') }}" class="btn-primary w-full"><x-ui.icon name="book-open" class="h-4 w-4" /> Open Bible</a>
                @if ($firstReading)
                    <a href="{{ route('bible', ['book' => $firstReading['slug'], 'chapter' => $firstReading['chapter']]) }}" class="btn-warm w-full">
                        Start challenge
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <article class="app-panel border-blue-200 bg-blue-50">
            <p class="app-eyebrow border-blue-200 bg-white text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Verse of the day</p>
            @if ($verse)
                <blockquote class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">
                    "{{ $verse->text }}"
                </blockquote>
                <p class="mt-4 text-sm font-black tracking-normal text-blue-900">
                    {{ $verse->book->name }} {{ $verse->chapter }}:{{ $verse->verse }} KJV
                </p>
                <a href="{{ route('bible', ['book' => $verse->book->slug, 'chapter' => $verse->chapter]) }}" class="mt-5 btn-secondary border-blue-200 text-blue-900 hover:bg-white">
                    Read chapter <x-ui.icon name="chevron-right" class="h-4 w-4" />
                </a>
            @else
                <p class="mt-4 rounded-2xl border border-dashed border-blue-200 bg-white p-4 text-sm text-slate-600">
                    The Bible has not been imported yet. Run `php artisan db:seed --class=BibleSeeder`.
                </p>
            @endif
        </article>

        <article class="app-panel border-amber-200 bg-amber-50">
            <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Daily affirmation</p>
            <p class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">
                {{ $affirmation['text'] }}
            </p>
            <p class="mt-4 text-sm font-black tracking-normal text-amber-900">{{ $affirmation['reference'] }}</p>
        </article>
    </section>

    <section class="app-panel border-emerald-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="star" class="h-4 w-4" /> Bible-in-a-year challenge</p>
                <h2 class="mt-3 app-section-title">Today&apos;s reading</h2>
            </div>
            @if ($challenge)
                <span class="inline-flex w-fit rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-black text-emerald-900">
                    Day {{ $challenge['day'] }} of {{ $challenge['days_in_year'] }}
                </span>
            @endif
        </div>

        @if ($challenge)
            <div class="mt-5">
                <div class="flex items-center justify-between gap-3 text-sm font-bold text-slate-700">
                    <span>{{ $challenge['completed_chapters'] }} of {{ $challenge['total_chapters'] }} chapters</span>
                    <span>{{ $challenge['progress_percent'] }}%</span>
                </div>
                <div class="mt-2 h-3 overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full rounded-full bg-emerald-700" style="width: {{ min(100, $challenge['progress_percent']) }}%"></div>
                </div>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($challenge['readings'] as $reading)
                    <a href="{{ route('bible', ['book' => $reading['slug'], 'chapter' => $reading['chapter']]) }}" class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-100">
                        <span class="block text-xs font-black uppercase tracking-normal text-emerald-800">{{ $reading['testament'] }}</span>
                        <span class="mt-1 block font-black tracking-normal text-slate-950">{{ $reading['book'] }} {{ $reading['chapter'] }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="mt-5 rounded-2xl border border-dashed border-emerald-200 bg-emerald-50 p-4 text-sm text-slate-600">
                Import the Bible seed data to generate the yearly reading challenge.
            </p>
        @endif
    </section>

    @if ($upcomingPlans->isNotEmpty())
        <section>
            <div class="mb-4">
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="journal" class="h-4 w-4" /> Next readings</p>
                <h2 class="mt-3 app-section-title">Seven-day challenge view</h2>
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($upcomingPlans as $plan)
                    <article class="app-surface border-sky-100 p-4">
                        <p class="text-xs font-black uppercase tracking-normal text-sky-800">{{ $plan['date']->format('D, M j') }}</p>
                        <h3 class="mt-2 font-black tracking-normal text-slate-950">Day {{ $plan['day'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $plan['reading_label'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>
